csvgit
======

command line personal log

# Installation

Clone the repo and customise the variables at the top of csvgit.sh - just your git username and project directory.

To check your git username (its your git user.name, nothing to do with github):

```
git config user.name
```

# Usage

For a simple csv output of today's work:

```
$ csvgit.sh
```

To check another day, tell it when to check from and until:

```
$ csvgit.sh "2000-12-30" "9am yesterday"
```

To check someone else's commits, tell it the time ranges and a username:

```
$ csvgit.sh "yesterday" "now" "John Smith"
```

To prettify the output and break it down based on hashtags, pipe the output to crunchem.php:

```
$ csvgit.sh | php crunchem.php
```