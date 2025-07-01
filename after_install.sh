#!/bin/bash
rm -rf /home/my-project/public_html-deploy/.htaccess /home/my-project/public_html-deploy/.git /home/my-project/public_html-deploy/.vscode /home/my-project/public_html-deploy/.hintrc /home/my-project/public_html-deploy/.gitignore /home/my-project/public_html-deploy/.gitlab-ci.yml 
rsync -avzp --exclude=appspec.yml --exclude=after_install.sh /home/my-project/public_html-deploy/ /home/my-project/public_html/
