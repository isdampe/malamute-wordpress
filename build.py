#!/usr/bin/env python
import os, sys

#
# Find all CodeMirror themes available
#
newFile = False
codeMirrorThemes = []
for (dirpath, dirnames, filenames) in os.walk('assets/vendor/cm/theme'):
	dirnames.sort()
	filenames.sort()
	for file in filenames:
		name = file.replace(".css", "")
		theme = {
			'name': name,
			'file': file
		}
		codeMirrorThemes.append(theme)

codeMirrorThemes.sort(key = lambda row: row['name'])

#
# Find all CodeMirror modes available
#
codeMirrorModes = []
codeMirrorModesFileBlacklist = ['test.js']
for (dirpath, dirnames, filenames) in os.walk('assets/vendor/cm/mode'):
	dirnames.sort()
	filenames.sort()
	for file in filenames:
		if file[-8:] == "_test.js":
			continue
		if file[-3:] == ".js":
			if file in codeMirrorModesFileBlacklist:
				continue
			name = file.replace(".js", "")
			mode = {
				'name': name,
				'file': file
			}
			codeMirrorModes.append(mode)

codeMirrorModes.sort(key = lambda row: row['name'])

#
# Build the buffer
#
with open("config/codemirror-config-template.php") as fh:
	configTemplate = fh.read()
	bfr = ""
	bfr = bfr + "$codemirror_themes = array(\n"
	for theme in codeMirrorThemes:
		bfr = bfr + "\t'%s' => array (\n\t\t'name' => '%s',\n\t\t'file' => '%s'\n\t),\n" % (theme['name'], theme['name'], theme['file'])

	bfr = bfr[:-2]
	bfr = bfr + "\n);\n\n"

	bfr = bfr + "$codemirror_modes = array(\n"
	for mode in codeMirrorModes:
		bfr = bfr + "\t'%s' => array (\n\t\t'name' => '%s',\n\t\t'file' => '%s'\n\t),\n" % (mode['name'], mode['name'], mode['file'])

	bfr = bfr[:-2]
	bfr = bfr + "\n);"

	newFile = configTemplate.replace("//{codemirror_build}", bfr)

if not newFile:
	print "Error: No buffer generated"
	sys.exit(1)

with open("config/codemirror-config.php", "w") as fh:
	fh.write(newFile)
	print "Wrote config/codemirror-config.php"