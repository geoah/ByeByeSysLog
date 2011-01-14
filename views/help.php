<div class="help">

	<h3>msg:</h3>
	<ul>
		<li>msg: test string <small>will be queried like '%test%string%'</small></li>
	</ul>
	
	<h3>host: <small>(Allows wildcards)</small></h3>
	<ul>
		<li>host: 10.0.0.1</li>
		<li>host: 10.0.0.*</li>
	</ul>
	
	<h3>date:</h3>
	<ul>
		<li>date: today</li>
		<li>date: yesterday</li>
		<li>date: today -1 week</li>
		<li>date: 2010-12-30 <small>date is parsed using php's strtotime()</small></li>
	</ul>
	
	<h3>from:</h3>
	<ul>
		<li>from: yesterday</li>
		<li>from: today -1 week</li>
		<li>from: 2010-12-30 13:00<small>date is parsed using php's strtotime()</small></li>
	</ul>
	
	<h3>to:</h3>
	<ul>
		<li>to: today</li>
		<li>to: today -2 days</li>
		<li>to: 2010-12-30 13:00<small>date is parsed using php's strtotime()</small></li>
	</ul>
<!--	
	<h3>facility: <small>(Allows wildcards)</small></h3>
	<ul>
		<li>facility: mail</li>
		<li>facility: http*</li>
	</ul>
-->
	<h3>program: <small>(Allows wildcards)</small></h3>
	<ul>
		<li>program: *mail</li>
		<li>program: httpd</li>
	</ul>
	
	<h3>pid: <small>(Allows wildcards)</small></h3>
	<ul>
		<li>pid: 19921</li>
		<li>pid: 19*1</li>
	</ul>
	
</div>