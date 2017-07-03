# This file is only loaded for the staging environment
# Customize it and copy it to staging.rb

# Simple Role Syntax
# ==================
# Supports bulk-adding hosts to roles, the primary
# server in each group is considered to be the first
# unless any hosts have the primary property set.
# Don't declare `role :all`, it's a meta role
role :web, %w{staging.example.com} # change this

set :stage, :staging

# Default deploy_to directory is /var/www/my_app
set :deploy_to, '/var/www/my_app' # change this

# Temp dir
set :tmp_dir, '/tmp'

# Extended Server Syntax
# ======================
# This can be used to drop a more detailed server
# definition into the server list. The second argument
# something that quacks like a hash can be used to set
# extended properties on the server.
server 'staging.example.com', # change this
	ssh_options: {
		user: 'deploy', # change this
		keys: %w(~/.ssh/deploy_rsa), # change this
		forward_agent: false,
		auth_methods: %w(publickey)
	}
