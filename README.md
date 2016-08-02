# plantuml-proxy
PlantUML proxy and documentation generator

# Installation 

## Ubuntu


### Graphviz
Install [graphiz](http://www.graphviz.org/) package 

`sudo apt-get install graphviz`

### Composer plantuml-proxy

Install composer package 

`composer require eslider/plantuml-proxy`

### Copy proxy script

Copy web proxy script to `web` folder depend on framework/installation.

Example:

`cp vendor/eslider/plantuml-proxy/web/pantuml-proxy.php web/pantuml-proxy.php`

## Using example

`http://localhost/pantuml-proxy.php?s=http://localhost/wherever/uml-file-example.puml`
