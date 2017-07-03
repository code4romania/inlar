<span class="button current">
	<img src="{{current.flag}}" class="flag">
	<span class="name">{{current.name}}</span>
	<span class="mobile-select">
		<i class="icon-arrow"></i>	
		<select>
			{{#each countries}}
				<option {{{select-attrs id}}}>{{name}}</option>
			{{/each}}
		</select>
	</span>
</span><?php
?><div class="dropdown-container">
	<span class="button dropdown-toggle"><?php _e('Another country', 'inlar'); ?><i class="icon-arrow-black"></i></span>
	<ul class="dropdown top-right">
		{{#each countries}}
			<li {{{list-attrs id}}}>{{name}}</li>
		{{/each}}
	</ul>
</div>
