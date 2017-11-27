<div class="card-header">
	{{#if img}}<img src="{{img}}" alt="">{{/if}}
	<strong class="name">{{name}}</strong>
</div>
<div class="card-content">
	{{#with meta}}
		{{#if phone}}
			<p><i class="icon-phone"></i><span class="phone">{{phone}}</span></p>
		{{/if}}
		{{#if email}}
			<p><i class="icon-email"></i><a href="mailto:{{email}}" class="email">{{email}}</a></p>
		{{/if}}
		{{#if url}}
			<p><i class="icon-link"></i><a target="_blank" href="{{url}}" class="map">{{url}}</a></p>
		{{/if}}
	{{/with}}
</div>
