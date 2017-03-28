namespace :submodules do
	desc "Fetch local submodule info"
	task :list do
		submodules = []

		on :local do
			lines = capture("git submodule --quiet foreach 'echo $path `git config --file $toplevel/.gitmodules submodule.$name.url`'").lines

			lines.each { |line|
				part = line.split(' ')
				submodules.push({
					'path' => part[0],
					'url'  => part[1],
				})
			}

			set :submodules, submodules
		end
	end

	desc "Clone bare repos for each submodule"
	task :clone => [:list] do
		on roles(:web) do
			fetch(:submodules).each { |submodule|
				full_path = File.join(deploy_to, fetch(:submodule_path), submodule['path'])

				if (SSHKit::Backend.current.test " [ -f #{full_path}/HEAD ] ")
					execute "git -C #{full_path} remote set-url origin #{submodule['url']}"
					execute "git -C #{full_path} remote update --prune"
				else
					execute "git clone --mirror #{submodule['url']} #{full_path}"
				end
			}
		end
	end

	desc "Build release archive for each submodule"
	task :release => [:clone] do
		on roles(:web) do
			branch = fetch(:branch)
			fetch(:submodules).each { |submodule|
				full_path = File.join(deploy_to, fetch(:submodule_path), submodule['path'])

				treeish = capture("git -C #{repo_path} rev-parse --quiet --verify #{branch}:#{submodule['path']}", raise_on_non_zero_exit: false)

				if treeish.empty?
					fatal "[ERROR] Couldn't fetch submodule info for #{branch}:#{submodule['path']}"
					abort
				else
					info "Found submodule tree-ish: #{treeish}"
					execute "git -C #{full_path} archive --prefix=#{submodule['path']}/ HEAD | tar -x -f - -C #{release_path}"
				end
			}
		end
	end
end
