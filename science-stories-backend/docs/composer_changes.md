GUIDLINES FOR COMPOSER CHANGES



### Please keep in mind before creating a merge 



- Add a extension in composer.json for merging the modules composer files

     "wikimedia/composer-merge-plugin": "*"

- Remove codeception files from repositories.

- Update config in composer.json

  "config": {
		"allow-plugins": {
			"yiisoft/yii2-composer": true,
                        "wikimedia/composer-merge-plugin": true
		}
	},

- Add the following code in the last of composer.json

	"extra": {
		"merge-plugin": {
			"include": [
				"./protected/modules/*/composer.json"
			],
			"recurse": true,
			"replace": false,
			"ignore-duplicates": true,
			"merge-dev": true,
			"merge-extra": false,
			"merge-extra-deep": false,
			"merge-scripts": true
		}
	}