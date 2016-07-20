# This script generates a new locale/en/LC_MESSAGES/messages.po and a new
# locale/nl_NL/LC_MESSAGES/messages.po based on gettext's msgmerge.
# The working directory must be script/ !!!
cd ..
cp locale/en/LC_MESSAGES/messages.po locale/en/LC_MESSAGES/messages.po.old
cp locale/nl_NL/LC_MESSAGES/messages.po locale/nl_NL/LC_MESSAGES/messages.po.old
find . -name "*.php" -exec xgettext --no-location -L php --no-wrap -j -o locale/en/LC_MESSAGES/messages.po --keyword=dptext {} \;
find . -name "*.tpl" -exec xgettext --no-location -L php --no-wrap -j -o locale/en/LC_MESSAGES/messages.po --keyword=dptext {} \;
find . -name "*.php" -exec xgettext -L php --no-wrap -F -j -o locale/en/LC_MESSAGES/messages.po --keyword=dptext {} \;
find . -name "*.tpl" -exec xgettext -L php --no-wrap -F -j -o locale/en/LC_MESSAGES/messages.po --keyword=dptext {} \;
msgmerge -F locale/nl_NL/LC_MESSAGES/messages.po.old locale/en/LC_MESSAGES/messages.po --output-file=locale/nl_NL/LC_MESSAGES/messages.po
cd script
