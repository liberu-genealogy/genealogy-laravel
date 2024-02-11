# Contributing

Contributions are **welcome** and will be fully **credited**. We accept contributions via Pull Requests on [Github](https://github.com/familytree365/genealogy).

## Setup

1. Download the project files from this github repo
2. If you are on windows and you have Git Bash installed on your system you can open it in the project folder and just run the following command:

```bash
./setup.sh
```

and everything should be installed automatically if you are using Linux you just run the script as you normally run scripts in the terminal.

NOTE 1: The script will ask you if you want to have your .env be overwritten by .env.example, in case you have already an .env configuration available please answer with "n" (No).

NOTE 2: This script will run seeders, please make sure you are aware of this and don't run this script if you don't want this to happen.

## Pull Requests

-   **[PSR-4 Coding Standard.]** The easiest way to apply the conventions is to install [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).
-   **Document any change in behaviour.** Make sure the `README.md` and any other relevant documentation are kept up-to-date.
-   **Create feature branches.** Don't ask us to pull from your master branch.
-   **One pull request per feature.** If you want to do more than one thing, send multiple pull requests.
-   **Send coherent history.** Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.
