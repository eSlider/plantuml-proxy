<?php
namespace Eslider;

/**
 * Class PlantUMLProxy
 *
 * @author Andriy Oblivantsev <eslider@gmail.com>
 */
class PlantUMLProxy
{
    static $files = array();

    /**
     * @param $url
     * @return string
     */
    public static function saveFile($url)
    {
        $remoteFileName = basename($url);
        $_url           = parse_url($url);
        $pathInfo       = pathinfo($_url["path"]);
        $uniqueDir      = sys_get_temp_dir() . "/plantuml-proxy/" . $_url["host"] . $pathInfo["dirname"];
        $fileSrc        = $uniqueDir . "/" . $pathInfo["basename"];
        $remoteFileDir  = dirname($url);
        $fileContent    = file_get_contents($url);

        if (in_array($remoteFileName, self::$files)) {
            return $fileSrc;
        }
        if (!is_dir($uniqueDir)) {
            mkdir($uniqueDir, 0777, true);
        }

        file_put_contents($fileSrc, $fileContent);
        self::$files[] = $remoteFileName;
        $includes      = self::getIncludes($fileContent);

        foreach ($includes as $fileName) {
            self::saveFile($remoteFileDir . "/" . $fileName);
        }
        return $fileSrc;
    }

    /**
     * @param $fileContent
     * @return array file paths
     */
    public static function getIncludes($fileContent)
    {
        preg_match_all("/\\!include (.+)/", $fileContent, $matches);
        return $matches[1];
    }

    /**
     * @param $url
     */
    public static function render($url)
    {
        if (strpos($url, "http") === 0) {
            $fileSrc  = PlantUMLProxy::saveFile($url);
            $pathInfo = pathinfo($fileSrc);
            $path     = escapeshellcmd($fileSrc);
            $imageSrc = $pathInfo["dirname"] . "/" . $pathInfo["filename"] . ".png";
            $jarFile  = self::getJarSrc();
            $stdOut   = `java -jar $jarFile $path`;
            header("Content-Type: image/png", true);
            die(file_get_contents($imageSrc));
        }
    }

    /**
     * @return string
     */
    protected static function getJarSrc()
    {
        return realpath(__DIR__ . "/../bin/plantuml.jar");
    }

    /**
     * @param $args
     */
    public static function generate($args)
    {
        $jarFile = static::getJarSrc();
        $path    = count($args) == 2 ? $args[1] : "./";

        foreach (explode("\n", `find $path -iname "*.puml"`) as $filename) {
            if (empty($filename)) {
                continue;
            }
            $stdOut = `java -jar $jarFile -nosuggestengine $filename`;
            echo "$filename - Größe: " . filesize($filename) . "\n";
            echo $stdOut;
        }
    }
}