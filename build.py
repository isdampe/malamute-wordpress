#!/usr/bin/env python
import os, sys

newFile = False
codeMirrorThemes = []
for (dirpath, dirnames, filenames) in os.walk('assets/vendor/cm/theme'):
	for file in filenames:
		name = file.replace(".css", "")
		theme = {
			'name': name,
			'file': file
		}
		codeMirrorThemes.append(theme)

with open("config/codemirror-config-template.php") as fh:
	configTemplate = fh.read()
	bfr = ""
	bfr = bfr + "$codemirror_themes = array(\n"
	for theme in codeMirrorThemes:
		bfr = bfr + "\t'%s' => array (\n\t\t'name' => '%s',\n\t\t'file' => '%s'\n\t),\n" % (theme['name'], theme['name'], theme['file'])

	bfr = bfr[:-2]
	bfr = bfr + "\n);"
	newFile = configTemplate.replace("//{codemirror_themes}", bfr)

if not newFile:
	print "Error: No buffer generated"
	sys.exit(1)

with open("config/codemirror-config.php", "w") as fh:
	fh.write(newFile)
	print "Wrote config/codemirror-config.php"