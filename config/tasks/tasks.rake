namespace :shared do
	task :make_shared_dir do
		on roles(:web) do
			execute "if [ ! -d #{shared_path}/files ]; then mkdir #{shared_path}/files; fi"
		end
	end

	task :make_symlinks do
		on roles(:web) do
			execute "if [ ! -h #{release_path}/shared ]; then ln -s #{shared_path}/files/ #{release_path}/shared; fi"
			execute "for p in `find -L #{release_path} -type l`; do t=`readlink $p | grep -o 'shared/.*$'`; mkdir -p #{release_path}/$t; done"
		end
	end
end

namespace :files do
	desc "Pulls the production files"
	task :pull do
		on roles(:web) do
			if fetch(:stage) != :production then
				puts "[ERROR] You must run files:pull from production with `cap production files:pull`"
			else
				roles(:web).each do |server|
					puts "rsync -avz --delete #{server}:#{shared_path}/files/ shared/files/"
					system "rsync -avz --delete #{server}:#{shared_path}/files/ shared/files/"
				end
			end
		end
	end

	desc "Pushes the production files to staging"
	task :push do
		on roles(:web) do
			if fetch(:stage) != :staging then
				puts "[ERROR] You must run files:push from staging with `cap staging files:push`"
			else
				roles(:web).each do |server|
					puts "rsync -avz --delete shared/files/ #{server}:#{shared_path}/files/"
					system "rsync -avz --delete shared/files/ #{server}:#{shared_path}/files/"
				end
			end
		end
	end
end

namespace :db do
	desc "Pulls the production database"
	task :pull do
		on roles(:web) do
			if fetch(:stage) != :production then
				puts "[ERROR] You must run db:pull from production with `cap production db:pull`"
			else
				puts "Hang on... this might take a while."

				random = rand( 10 ** 5 ).to_s.rjust( 5, '0' )
				db = fetch(:wpdb).fetch(:production)

				tmp = "#{fetch(:tmp_dir, '/tmp')}/wpdb-#{random}.sql.bz2"

				execute "mysqldump -u #{db[:user]} -h #{db[:host]} -p#{db[:password]} #{db[:name]} | bzip2 -9 > #{tmp}"

				download!(tmp, "shared/db/production.sql.bz2")

				execute "rm #{tmp}"

				puts "Database downloaded from production"
			end
		end
	end

	desc "Pushes the production database to staging"
	task :push do
		on roles(:web) do
			if fetch(:stage) != :staging then
				puts "[ERROR] You must run db:push from staging with `cap staging db:push`"
			else
				puts "Hang on... this might take a while."

				dbfile = 'shared/db/production.sql.bz2'

				if File.exists?(dbfile) then
					random = rand( 10 ** 5 ).to_s.rjust( 5, '0' )
					db = fetch(:wpdb).fetch(:staging)

					tmp = "#{fetch(:tmp_dir, '/tmp')}/wpdb-#{random}.sql.bz2"

					upload!(dbfile, tmp)

					execute "bzcat #{tmp} | mysql -u #{db[:user]} -h #{db[:host]} -p#{db[:password]} #{db[:name]}  && rm #{tmp}"

					system "rm #{dbfile}"

					puts "Database uploaded to staging"
				else
					puts "[ERROR] Database dump not found! You must first run db:pull from production with `cap production db:pull`"
				end
			end
		end
	end
end

namespace :wp do
	desc "Sets the database credentials (and other settings) in wp-config.php"
	task :config do
		on roles(:web) do
			stage = fetch(:stage).to_s
			conf = fetch(:wpdb).fetch(stage)
			salts = fetch(:salts).fetch(stage)
			conf = {
				:'%%WP_STAGE%%'             => stage,

				:'%%DB_NAME%%'              => conf['name'],
				:'%%DB_USER%%'              => conf['user'],
				:'%%DB_PASSWORD%%'          => conf['password'],
				:'%%DB_HOST%%'              => conf['host'],

				:'%%AUTH_KEY%%'             => salts['auth_key'],
				:'%%SECURE_AUTH_KEY%%'      => salts['secure_auth_key'],
				:'%%LOGGED_IN_KEY%%'        => salts['logged_in_key'],
				:'%%NONCE_KEY%%'            => salts['nonce_key'],
				:'%%AUTH_SALT%%'            => salts['auth_salt'],
				:'%%SECURE_AUTH_SALT%%'     => salts['secure_auth_salt'],
				:'%%LOGGED_IN_SALT%%'       => salts['logged_in_salt'],
				:'%%NONCE_SALT%%'           => salts['nonce_salt'],
			}

			info "Preparing wp-config.php"
			conf.each do |k,v|
				v.gsub!(/(&|\\|\/)/, '\/')
				debug capture "sed -i 's/#{k}/#{v}/' #{release_path}/wp-config.php", :roles => :web
			end
			info "Done preparing wp-config.php"
		end
	end

	desc "Remove wp-content dir"
	task :cleanup do
		on roles(:web) do
			execute "if [ -d #{release_path}/wp/wp-content ]; then rm -rf #{release_path}/wp/wp-content; fi"
		end
	end
end
