# Load YAML
require 'yaml'

# Load DSL and Setup Up Stages
require 'capistrano/setup'

# Includes default deployment tasks
require 'capistrano/deploy'

# Git
require "capistrano/scm/git"
install_plugin Capistrano::SCM::Git

# Loads custom tasks from `lib/capistrano/tasks' if you have any defined.
# Dir.glob('config/tasks/*.rb').each { |r| import r }
load 'config/tasks/tasks.rb'
load 'config/tasks/hooks.rb'
