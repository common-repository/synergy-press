<?php
/**
 * SynergyPress WordPress plugin.
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.0
 * @package synergy-press
 **/

// Make sure we don't expose any info if called directly.
if ( ! defined( 'SYNERGY_PRESS' ) ) :
	return;
endif;
?>

<div class="fs-content">
	<div id="tips-etag" class="tips-block close underline mb-2">
		<strong>Etags:</strong>
		<p class="fs-item-description fs-strong"> An etag should look like this: </p>
		<p class="fs-item-description">
			<span class="prettyprint">data-fs-etag="onclick:my-element"</span>
			<span class="prettyprint">data-fs-etag="onfocus:my-input"</span>
			The first part is the event to listen to "onclick". The next part is the tag "my-element", they are bough used to establish a relationship. Example
			<a class="fs-btn-icon"
				href="<?php echo esc_url( 'https://formsynergy.com/documentation/options-and-parameters/#fs-etag' ); ?>"
				target="_blank" title="Open the Form Synergy Console in a new tab"> Docs <span
				class="dashicons dashicons-external"></span>
			</a>
		</p>
		<pre class="prettyprint linenums">
// Parameter
{
	"etag": "onfocus:my-input"
}</pre>
	</div>

	<div id="tips-params" class="tips-block close underline mb-2">
		<strong>Parameters:</strong>
		<p class="fs-item-description">
		Any data sent through the parameters will be promptly intercepted and applied.
		</p>
		<pre class="prettyprint linenums">
// Parameter
{
	"params": {
		"trigger": {
			"moduleid": "...the module id"
		},
		"count": 3
	}
}</pre>
	</div>

	<div id="tips-moduleid" class="tips-block close underline mb-2">
		<strong>Module ID:</strong>
		<p class="fs-item-description">
			The ID of the module to load into the interaction.
		</p>
		<pre class="prettyprint linenums">
// Parameter
{
	"moduleid": "...the module id"
}</pre>
	</div>

	<div id="tips-options" class="tips-block close underline mb-2">
		<strong>Display options:</strong>
		<p class="fs-item-description">
			Related to position, display, placement, theme, is set in the options.
		</p>
	</div>

	<div id="tips-trigger" class="tips-block close underline mb-2">
		<strong>Trigger:</strong>
		Used load the body of an interaction. You must specify a moduleid.
		<pre class="prettyprint linenums">
// Parameter
{
	"trigger": {
		"moduleid": "...the module id"
	}
}</pre>
	</div>
	<div id="tips-count" class="tips-block close underline mb-2">
		<strong>Count:</strong>
		Will trigger the interaction once the associated event is repeated and surpasses the count.
		<pre class="prettyprint linenums">
// Parameter
{
	"count": 5
}</pre>
	</div>

	<div id="tips-className" class="tips-block close underline mb-2">
		<strong>Class name:</strong>
		Used to wrap the body of an interaction with a custom class name.
		<pre class="prettyprint linenums">
// Parameter
{
	"className": "my-custom-class-name"
}</pre>
	</div>

	<div id="tips-rel" class="tips-block close underline mb-2">
		<strong>Contextual data:</strong>
		Used to inject relevant or contextual data, into an interaction.
		<pre class="prettyprint linenums">
// Parameter
{
	"rel": {
		"interactionName": {
			"$customKey": "$customValue"
		}
	}
}</pre>
	</div>

	<div id="tips-el" class="tips-block close underline mb-2">
		<strong>Query selector:</strong>
		Used to associate display options with its parameters, by using the module ID as a common denominator.
		Don't forget the @ sign.
		<pre class="prettyprint linenums">
// Display option
{
	"el": "@...the module id" 
}</pre>
	</div>
	<div id="tips-opt" class="tips-block close underline mb-2">
		<strong>Display options:</strong>
		<p class="description">Contains anything regarding placement and positions.</p>
		<p class="description">* You can also apply an offset for each interaction. (in pixels) 
			<a class="fs-btn-icon"
				href="<?php echo esc_url( 'https://formsynergy.com/documentation/options-and-parameters/#fs-opt-offset' ); ?>"
				target="_blank" title="Open the Form Synergy Console in a new tab"> Docs <span
				class="dashicons dashicons-external"></span> </a>
		</p>
		<ul class="inline-badge">
			<li>up</li>
			<li>right</li>
			<li>down</li>
			<li>left</li>
		</ul>
		<pre class="prettyprint linenums">
// Display option
"opt": {
		"display": "fixed",
		"placement": "centered",
		"size": "lg",
		"theme": "white",
		/** Applying additional offset */
		"up": 50,
		"left": 100
}</pre>
	</div>

	<div id="tips-display" class="tips-block close underline mb-2">
		<strong>Display:</strong>
		<p class="description">Note: The display tag is only required, if not using the default display type.</p>
		<ul class="inline-badge">
			<li>Default</li>
			<li>Fixed</li>
			<li>Embed</li>
		</ul>
		<pre class="prettyprint linenums">
// Display option
{
	"display": "fixed"
}</pre>
	</div>
	<div id="tips-placement" class="tips-block close underline mb-2">
		<strong>Placements for default display:</strong>

		<ul class="inline-badge">
			<li>top</li>
			<li>right</li>
			<li>bottom</li>
			<li>left</li>
		</ul>
		<p class="description">Start and End can be used with default placement</p>
		<ul class="inline-badge">
			<li>start</li>
			<li>end</li>
		</ul>
		<pre class="prettyprint linenums">
// Display option
{
	"placement": "left-start"
}</pre>
	<strong>Placements for fixed display:</strong>

	<ul class="inline-badge">
		<li>upper</li>
		<li>lower</li>
		<li>centered</li>
		<li>top</li>
		<li>right</li>
		<li>bottom</li>
		<li>left</li>
	</ul>
	<p class="description">Start and End can be used with fixed placements except for "upper, lower and centered"</p>
	<ul class="inline-badge">
		<li>start</li>
		<li>end</li>
	</ul>
	<pre class="prettyprint linenums">
// Display option
{
"display": "fixed",
"placement": "upper"
}</pre>
		<strong>Embed display:</strong>
		<pre class="prettyprint linenums">
// Display option
{
	"display": "embed"
}</pre>
	</div>

	<div id="tips-size" class="tips-block close underline mb-2">
		<strong>Sizes:</strong>

		<ul class="inline-badge">
			<li>xs</li>
			<li>sm</li>
			<li>lg</li>
			<li>xl</li>
			<li>full: Only available when using embed display</li>
		</ul>
		<pre class="prettyprint linenums">
// Display option
{
	"display": "embed",
	"size": "full"
}</pre>
	</div>

	<div id="tips-theme" class="tips-block close underline mb-2">
		<strong>Themes:</strong>
		<ul class="inline-badge">
			<li>white</li>
			<li>white-translucent</li>
			<li>dark</li>
			<li>dark-translucent</li>
			<li>translucent</li>
		</ul>
		<p class="description">Customs themes can be specified as well.</p>
		<pre class="prettyprint linenums">
// Display option           
{
	"theme": "white-translucent"
}</pre>
	</div>

	<div id="tips-howto" class="tips-block close underline mb-2">
		<strong>Help and tips:</strong>
		<p class="fs-item-description">
			When editing parameters or display options, <strong>double click on a key for more tips</strong>.
		</p>
	</div>
	<div id="tips-use-class" class="tips-block mb-3 close">
		<strong class="pt-3">Using a reference class name:</strong>
		<p class="fs-item-description">
			A reference class name is generated for each module, linking its options and parameters. Simply add the class name to the element of choice to trigger an interaction.
		</p>
		<strong class="mt-3">Preventing autoloading:</strong>
		<p class="fs-item-description">
			Some advanced implementation such as exit strategies, onScroll, and any custom events, are not supported by the plugin and will require some HTML editing. 
			In this case, autoloading should be prevented on the module. 
			<a class="fs-btn-icon"
				href="<?php echo esc_url( 'https://formsynergy.com/documentation/javascript-api/#fs-default-events' ); ?>"
				target="_blank" title="Open the Form Synergy Console in a new tab"> Docs <span
					class="dashicons dashicons-external"></span> </a>
		</p>
	</div>
	<div id="tips-welcome" class="open-block mb-3">
		<p class="fs-item-description"><strong>Tips: </strong> This help section will automatically display relevant tips and available options.</p>
	</div>
</div>
