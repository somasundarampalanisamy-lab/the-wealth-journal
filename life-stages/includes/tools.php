<?php
/**
 * Calculator markup for [ls_tools].
 *
 * @package life-stages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="ls-tools">
	<section class="ls-tool stage-starting-out" id="sip" data-ls-tool="sip" aria-labelledby="ls-sip-h">
		<h2 id="ls-sip-h">SIP growth calculator</h2>
		<p>See what investing a fixed amount every month could grow into.</p>
		<div class="ls-tool-grid">
			<label>Monthly SIP (₹)<input type="number" inputmode="numeric" min="100" step="100" value="2000" data-k="amount"></label>
			<label>Years<input type="number" inputmode="numeric" min="1" max="50" step="1" value="25" data-k="years"></label>
			<label>Expected yearly return (%)<input type="number" inputmode="decimal" min="0" max="30" step="0.5" value="12" data-k="rate"></label>
		</div>
		<div class="ls-tool-result" aria-live="polite">
			<div><span>You invest</span><strong data-out="invested">–</strong></div>
			<div><span>Estimated value</span><strong data-out="value">–</strong></div>
			<div><span>Growth from compounding</span><strong data-out="gain">–</strong></div>
		</div>
		<p class="ls-tool-note">Illustration only. Real returns go up and down from year to year and are never guaranteed.</p>
	</section>

	<section class="ls-tool stage-growing" id="inflation" data-ls-tool="inflation" aria-labelledby="ls-inf-h">
		<h2 id="ls-inf-h">Inflation calculator</h2>
		<p>Find out what something will cost in the future, and what today’s money will really be worth.</p>
		<div class="ls-tool-grid">
			<label>Amount today (₹)<input type="number" inputmode="numeric" min="1" step="100" value="50000" data-k="amount"></label>
			<label>Years from now<input type="number" inputmode="numeric" min="1" max="50" step="1" value="10" data-k="years"></label>
			<label>Yearly inflation (%)<input type="number" inputmode="decimal" min="0" max="20" step="0.5" value="6" data-k="rate"></label>
		</div>
		<div class="ls-tool-result" aria-live="polite">
			<div><span>The same things will cost</span><strong data-out="future">–</strong></div>
			<div><span>Today’s amount will buy what costs today</span><strong data-out="power">–</strong></div>
		</div>
		<p class="ls-tool-note">Inflation varies year to year. 5–7% is a common planning range for India.</p>
	</section>

	<section class="ls-tool stage-growing" id="rebalance" data-ls-tool="rebalance" aria-labelledby="ls-reb-h">
		<h2 id="ls-reb-h">60/40 rebalancing calculator</h2>
		<p>Enter what you hold today. We’ll show how much to move to get back to your target mix.</p>
		<div class="ls-tool-grid">
			<label>Equity holdings (₹)<input type="number" inputmode="numeric" min="0" step="1000" value="480000" data-k="equity"></label>
			<label>Debt holdings (₹)<input type="number" inputmode="numeric" min="0" step="1000" value="420000" data-k="debt"></label>
			<label>Target equity (%)<input type="number" inputmode="numeric" min="0" max="100" step="5" value="60" data-k="target"></label>
		</div>
		<div class="ls-tool-result" aria-live="polite">
			<div><span>Your mix today</span><strong data-out="mix">–</strong></div>
			<div><span>What to do</span><strong data-out="action">–</strong></div>
		</div>
		<p class="ls-tool-note">Many investors rebalance once a year, or when the mix drifts more than 5 percentage points. Check exit loads and capital gains tax before you move money.</p>
	</section>
</div>
