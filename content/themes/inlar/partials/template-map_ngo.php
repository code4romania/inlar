<div class="entry-partners">
	{{~#each ngos~}}
		{{#ifeq properties.country_id ../country_id~}}
			<div class="entry" itemscope="itemscope" itemtype="http://schema.org/Organization">
				{{#if properties.img}}
					<img src="{{properties.img}}" class="size-partner-logo-large" alt="">
				{{/if}}
				<div class="entry-content">
					<h2 class="entry-title" itemprop="name">{{properties.name}}</h2>{{!-- 
					--}}<div class="entry-desc">
						<p>{{{properties.desc}}}</p>
						{{#with properties.meta}}
							<ul class="ngo-meta">
								{{#if phone}}
									<li><i class="icon-phone"></i><span class="phone">{{phone}}</span></li>
								{{/if}}
								{{#if email}}
									<li><i class="icon-email"></i><a href="mailto:{{email}}" class="email">{{email}}</a></li>
								{{/if}}
								{{#if url}}
									<li><i class="icon-link"></i><a target="_blank" href="{{url}}" class="map">{{url}}</a></li>
								{{/if}}
								{{#if address}}
									<li><i class="icon-map"></i><address>{{address}}</address></li>
								{{/if}}
							</ul>
						{{/with}}
					</div>
				</div>
			</div>
		{{~/ifeq}}
	{{~/each~}}
</div>
