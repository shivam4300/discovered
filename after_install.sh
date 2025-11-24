#!/bin/bash
rm -rf /home/shivam/public_html-deploy/.htaccess /home/shivam/public_html-deploy/.git /home/shivam/public_html-deploy/.vscode /home/shivam/public_html-deploy/.hintrc /home/shivam/public_html-deploy/.gitignore /home/shivam/public_html-deploy/.gitlab-ci.yml 
rsync -avzp --exclude=appspec.yml --exclude=after_install.sh /home/shivam/public_html-deploy/ /home/shivam/public_html/
