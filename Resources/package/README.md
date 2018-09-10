# Test tools bundle packages

This directory contains all the _packages_ that this bundle can install with `test-tools:init` command.

## Structure

Each `package` is located to separated directory (package name is directory name). Within these directories
you can use following structure:

```
test-tools-bundle
 └──Resources/                                  * Resources
     └──package                                 * Directory which contains all the packages
         └──ecs                                 * Individual package
             ├── config                         * Package configs, all files from this folder are added to project root
             |    └── easy-coding-standard.yaml * Tool configuration file
             ├── .gitignore                     * gitignore file, see note 1
             ├── composer.json                  * Package dependencies, see note 1
             └── README.md                      * Package documentation, see note 1
             
note 1) these files are copied under `vendor-bin/_package_/` directory
```

Easiest way to add new package is just check structure of some existing package.
