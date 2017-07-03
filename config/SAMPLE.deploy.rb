# Customize this file, and then copy it to deploy.rb

# lock capistrano version
lock '3.8.0'

set :application, "INLAR"
set :repo_url, "git@github.com:code4romania/inlar.git"

# Default branch is :master
ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }
#set :branch, 'develop'

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
set :log_level, :info

# Submodules bare repo dir, relative to each stage's :deploy_to
set :submodule_path, 'submodules/'

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# set :linked_files, %w{config/database.yml}

# Default value for linked_dirs is []
# set :linked_dirs, %w{bin log tmp/pids tmp/cache tmp/sockets vendor/bundle public/system}

# Default value for keep_releases is 5
set :keep_releases, 5

# The domain name used for your staging environment
set :staging_domain, 'staging.example.com'

# This should be the same as :deploy_to in production.rb
set :production_deploy_to, '/var/www/my_app'

# Logger configuration
set :format, :airbrussh
set :format_options, command_output: true, log_file: "shared/capistrano.log", color: :auto, truncate: :auto

# Database
# Set the values for host, user, pass, and name for both production and staging.
set :wpdb, YAML.load_file('config/secrets/db.yaml')

# Salts
set :salts, YAML.load_file('config/secrets/salts.yaml')

# You're not done! You must also configure deploy/production.rb and deploy/staging.rb
