#!/bin/bash

since="9am";
untl="now";
username="David Godfrey"; # Your username in the logs
projects_directory="/home/david/Sites"; # full path to your 'projects' directory

pushd ${projects_directory} > /dev/null; # change to projects directory
find . -maxdepth 2 -name ".git" | # find all git subfolders
tr "/" "\n" | grep -v "^\.$" | grep -v "^\.git$" | # only want the top level folder name
while read project_dir;
do
  pushd "$project_dir" > /dev/null; # change into project
    {
      git log --all --since="${1:-$since}" --until="${2:-$untl}" --pretty=format:'%an,%ai,%s' | # get log between given times
        grep "${3:-$username}" | # crop down to only your commits
        sed -E "s/${3:-$username},//;s/, #/,#/g;" # hide your username, and the non-offset timezone
    } | sed "s/\([+-][0-9]\{4,4\}\),/\1,#${project_dir} /;" # prefix commits with the project name (optional)
  popd > /dev/null;
done | sort | grep -v "WIP on master:" | grep -v "index on master:" | grep -v "Merge branch '";
popd > /dev/null;