# Load YAML
require 'yaml'

# Load JSON
require 'json'

# Load DSL and Setup Up Stages
require 'capistrano/setup'

# Includes default deployment tasks
require 'capistrano/deploy'

# Git
require "capistrano/scm/git"
install_plugin Capistrano::SCM::Git

# Loads custom tasks from `config/tasks' if you have any defined.
Dir.glob('config/tasks/*.rake').each { |r| import r }
