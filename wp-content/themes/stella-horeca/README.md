# Installation guide
Clone or download this theme from Github and add it into the themes directory in your website.
Remane the folder to your websites name.
Replace all of the "StarterTheme" string with your websites name and the "startertheme" string with your lowercased websites name without any whitespace (fore example instead of the string "The Theme" use "thetheme").

## Install dependencies
Run the commands "composer install" and "npm install" to install the dependencies.

## Compile JS and CSS code
Run the command "gulp whatch" to compile the JS and SCSS files.

## Add ACF blocks
Just copy/paste the files from _blocks and _acf into the blocks and acf folders.

## Create blocks via NPM
You can also copy existing blocks, or create new ones via NPM CLI. For that, you need to install our NPM package.

## Install out NPM package
Rund the next command to install our NPM package: "npm install ba-block-cli"

### Create a new block
To create a new block, run the next command: 'ba-block cr -n "Test block"'

### Create a new block
To copy an existing block, run the next command: 'ba-block cp -n "Hero"'

### Remove an existing blocak
To remove an existing block, run the next command: 'ba-block rm -n "Test block"'
