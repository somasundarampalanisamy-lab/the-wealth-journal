<?php
/**
 * Sample articles. Returned as an array; built with the helpers in blocks.php.
 *
 * Keys: slug, title, excerpt, cats (category slugs), stages, order (learning
 * path position), days (published N days ago), content.
 *
 * @package life-stages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(

	/* ---------------- Starting Out ---------------- */

	array(
		'slug'    => 'ctc-vs-in-hand-salary',
		'title'   => 'Your Offer Letter Says ₹8 LPA. Here’s Why Your Bank Account Won’t.',
		'excerpt' => 'CTC is the price on the menu, not what lands on your plate. Here’s where the missing money goes, and how to work out your real monthly salary before day one.',
		'cats'    => array( 'payslip-ctc' ),
		'stages'  => array( 'starting-out' ),
		'order'   => 1,
		'days'    => 2,
		'content' =>
			ls_p( 'You open your offer letter, see <strong>₹8 LPA</strong>, divide by 12 and start planning around ₹66,667 a month. Then the first salary arrives and it’s noticeably less. Nothing has gone wrong. You’ve just met the difference between CTC and take-home pay.' )
			. ls_h( 'CTC means “cost to company”, not “cash to you”' )
			. ls_p( 'CTC is everything your employer spends on you in a year. Some of it is paid monthly, some once a year, and some never reaches your bank account at all because it goes into savings or insurance on your behalf.' )
			. ls_analogy( '<strong>Think of a pizza.</strong> CTC is the menu price. By the time the box reaches you, a few slices have been set aside for your future self, the government and some insurance. You still paid for the whole pizza; you just don’t get to eat all of it today.' )
			. ls_h( 'Where the missing slices go' )
			. ls_table(
				array( 'Part of CTC', 'What it is', 'In your monthly pay?' ),
				array(
					array( 'Employer’s PF contribution', 'Your employer’s share into your Provident Fund', 'No, it goes to your EPF account' ),
					array( 'Your PF contribution', 'Usually 12% of your basic pay, saved for retirement', 'Deducted from your pay' ),
					array( 'Gratuity', 'A lump sum paid after 5+ years with one employer', 'No' ),
					array( 'Insurance', 'Group health or life cover', 'No' ),
					array( 'Variable pay / bonus', 'Paid yearly or quarterly, if targets are met', 'No, not every month' ),
					array( 'Professional tax', 'A small state tax, up to ₹2,500 a year', 'Deducted monthly in many states' ),
					array( 'Income tax (TDS)', 'Tax deducted every month based on your regime', 'Deducted from your pay' ),
				)
			)
			. ls_h( 'How to estimate your in-hand salary' )
			. ls_list(
				array(
					'Start with your CTC and subtract variable pay, gratuity, insurance and the employer’s PF share.',
					'Divide what’s left by 12. That’s roughly your monthly gross salary.',
					'Subtract your own PF contribution, professional tax and monthly TDS.',
					'What remains is your <strong>net pay</strong>, the number to build your budget around.',
				),
				true
			)
			. ls_callout( 'Your one action', 'Email HR and ask for your <strong>monthly salary breakdown</strong>. Look for “net pay” or “take-home”. Budget with that number, never with CTC ÷ 12.' ),
	),

	array(
		'slug'    => 'how-to-read-salary-slip',
		'title'   => 'How to Read Your First Payslip (Every Line Explained)',
		'excerpt' => 'Your payslip is an itemised bill for your month of work. Here’s what every line means and the three things to check each month.',
		'cats'    => array( 'payslip-ctc' ),
		'stages'  => array( 'starting-out' ),
		'order'   => 2,
		'days'    => 9,
		'content' =>
			ls_p( 'Most people glance at the last line of their payslip and close the PDF. That’s like paying a restaurant bill without checking it. Five minutes with your payslip each month can catch mistakes and teach you a lot about where your money goes.' )
			. ls_analogy( '<strong>A payslip is an itemised restaurant bill.</strong> The top half lists what you ordered (your earnings). The bottom half lists the taxes and charges (deductions). The total at the end is what you actually take home.' )
			. ls_h( 'The earnings side' )
			. ls_list(
				array(
					'<strong>Basic pay:</strong> the foundation of your salary. PF and several other amounts are calculated as a percentage of it.',
					'<strong>HRA (House Rent Allowance):</strong> money towards rent. Under the old tax regime, part of it can be tax-free if you actually pay rent.',
					'<strong>Special allowance:</strong> a flexible top-up that is usually fully taxable.',
					'<strong>Other allowances:</strong> things like internet, meal cards or learning budgets, depending on your company.',
				)
			)
			. ls_h( 'The deductions side' )
			. ls_list(
				array(
					'<strong>EPF (Employee Provident Fund):</strong> your retirement savings, usually 12% of basic pay. Your employer adds its own share.',
					'<strong>Professional tax:</strong> a small state tax, in states that charge it.',
					'<strong>TDS (Tax Deducted at Source):</strong> income tax taken out every month, so you don’t face one big bill at year end.',
				)
			)
			. ls_h( 'Three things to check every month' )
			. ls_list(
				array(
					'Your <strong>net pay</strong> matches what landed in your bank account.',
					'Your <strong>PF deduction</strong> also appears in your EPF passbook on the EPFO member portal.',
					'Your <strong>TDS</strong> looks steady. A sudden jump often means a missing investment declaration or a regime change.',
				),
				true
			)
			. ls_callout( 'Your one action', 'Create a folder called “Payslips” and save every one. You’ll need them for loans, visas, tax filing and job changes, and future you will be grateful.' ),
	),

	array(
		'slug'    => 'first-salary-checklist',
		'title'   => 'Your First Salary Just Landed. Do These 7 Things Before Spending a Rupee.',
		'excerpt' => 'The habits you set with your first salary tend to stick for years. Seven simple steps to start strong, in the order that matters.',
		'cats'    => array( 'budgeting-emergency-funds' ),
		'stages'  => array( 'starting-out' ),
		'order'   => 3,
		'days'    => 13,
		'content' =>
			ls_p( 'Your first salary feels like freedom, and it should. Celebrate. But before the month quietly spends it for you, take one evening to set things up. These seven steps take a couple of hours and will shape your finances for years.' )
			. ls_analogy( '<strong>Charge your phone before the trip.</strong> You don’t wait until you’re at 3% to look for a socket. Set up your savings first, then head out and enjoy the month.' )
			. ls_list(
				array(
					'<strong>Celebrate, with a limit.</strong> Treat your family or yourself, but decide the amount in advance.',
					'<strong>Check your payslip</strong> against what reached your bank account.',
					'<strong>Pay yourself first.</strong> Set an automatic transfer of 10–20% to a separate savings account for the day after payday.',
					'<strong>Start an emergency fund.</strong> Aim for 3–6 months of expenses, kept somewhere safe and easy to reach.',
					'<strong>Split the rest with 50/30/20:</strong> roughly 50% needs, 30% wants, 20% savings and investing.',
					'<strong>Check your health cover.</strong> Find out what your employer’s group insurance covers, and whether it includes your parents.',
					'<strong>Say no to “easy” debt.</strong> Skip buy-now-pay-later on gadgets and don’t treat a credit card limit as extra income.',
				),
				true
			)
			. ls_h( 'Why the order matters' )
			. ls_p( 'Steps 3 and 4 come before spending because good intentions fade by the 20th of the month. Automation removes willpower from the equation. Once the transfer runs on its own, you can spend the rest without guilt.' )
			. ls_callout( 'Your one action', 'Set up the automatic “pay yourself first” transfer today. Even 10% is a great start, and you can raise it with every increment.' ),
	),

	array(
		'slug'    => 'what-is-inflation',
		'title'   => 'Why ₹100 Today Won’t Buy the Same Samosa Plate in 2036',
		'excerpt' => 'Inflation quietly shrinks what your money can buy. Here’s how it works, why savings accounts alone fall behind, and what to do about it.',
		'cats'    => array( 'compounding-inflation' ),
		'stages'  => array( 'starting-out' ),
		'order'   => 4,
		'days'    => 18,
		'content' =>
			ls_p( 'Remember what a samosa cost in your first year of college? It probably costs noticeably more now. The samosa didn’t get bigger. Your money just buys a little less each year. That slow leak is called <strong>inflation</strong>.' )
			. ls_analogy( '<strong>Inflation is a slow puncture in your money.</strong> You don’t notice it day to day, but leave it for ten years and the tyre is visibly flatter.' )
			. ls_h( 'What it does to your money' )
			. ls_p( 'In India, consumer price inflation has often run at around 5–6% a year. At 6%, prices roughly double every 12 years. A samosa plate that costs ₹100 today would cost about <strong>₹179</strong> in 2036.' )
			. ls_table(
				array( 'Today', 'In 10 years at 6% inflation', 'In 20 years' ),
				array(
					array( '₹100 samosa plate', 'about ₹179', 'about ₹321' ),
					array( '₹15,000 monthly rent', 'about ₹26,900', 'about ₹48,100' ),
					array( '₹50,000 laptop', 'about ₹89,500', 'about ₹1.6 lakh' ),
				)
			)
			. ls_h( 'Why “safe” savings can lose money' )
			. ls_p( 'If your savings account pays less than inflation, your money is shrinking in real terms, even though the balance goes up. That’s why long-term money needs to be invested in things that can grow faster than prices, like businesses (through equity) balanced with stable debt.' )
			. ls_callout( 'Your one action', 'Try our <a href="{{url:tools}}#inflation">inflation calculator</a> with one goal you have, like a bike or a trip abroad, and see what it will really cost.' ),
	),

	array(
		'slug'    => 'old-vs-new-tax-regime-freshers',
		'title'   => 'Old vs New Tax Regime: Which One Should a Fresher Pick?',
		'excerpt' => 'HR wants your answer by Friday. Here’s a simple way to decide, without memorising tax slabs.',
		'cats'    => array( 'tax-regimes-filing' ),
		'stages'  => array( 'starting-out', 'growing' ),
		'order'   => 5,
		'days'    => 23,
		'content' =>
			ls_p( 'In your first week, HR will ask you to choose a tax regime. It decides how much TDS is taken from your salary each month. Most freshers pick at random. You don’t have to.' )
			. ls_analogy( '<strong>It’s like choosing a mobile plan.</strong> The new regime is the simple unlimited plan: lower rates, very few extras. The old regime is the pay-per-use plan with discounts: higher rates, but you can claim deductions if you actually use them.' )
			. ls_h( 'The two regimes in one table' )
			. ls_table(
				array( '', 'New regime', 'Old regime' ),
				array(
					array( 'Tax rates', 'Lower', 'Higher' ),
					array( 'Deductions (80C, HRA, etc.)', 'Very few allowed', 'Many allowed' ),
					array( 'Paperwork', 'Minimal', 'Proofs and declarations needed' ),
					array( 'Default?', 'Yes', 'Only if you choose it' ),
					array( 'Best for', 'People with few deductions', 'People with large rent, home loan or 80C investments' ),
				)
			)
			. ls_h( 'A simple way to decide' )
			. ls_list(
				array(
					'List the deductions you would <em>genuinely</em> claim this year: rent you pay (for HRA), EPF and other 80C investments, health insurance premiums, home loan interest.',
					'If the list is short, which is common for freshers, the new regime usually works out better.',
					'If it’s long, compare both using the Income Tax Department’s official tax calculator before you decide.',
					'Remember that salaried employees can usually switch regimes each year when filing their return.',
				),
				true
			)
			. ls_p( 'Slabs, rebates and the standard deduction change with almost every Union Budget, so always check the current year’s numbers on the official income tax portal rather than an old blog post (including this one).' )
			. ls_callout( 'Your one action', 'Write down your real deductions for this year on one sheet of paper. If you can’t think of many, the new regime is probably your answer.' ),
	),

	array(
		'slug'    => 'power-of-compounding',
		'title'   => 'The Most Powerful Force in Finance Is Just Patience',
		'excerpt' => 'Priya invests less than Arjun but ends up with nearly twice as much. The only difference is when they started.',
		'cats'    => array( 'compounding-inflation' ),
		'stages'  => array( 'starting-out' ),
		'order'   => 6,
		'days'    => 28,
		'content' =>
			ls_p( 'Compounding means your money earns returns, and then those returns earn returns of their own. It sounds modest. Over decades, it’s the biggest force in personal finance, and time is the ingredient that makes it work.' )
			. ls_analogy( '<strong>A snowball rolling downhill.</strong> At first it’s small and slow. Give it enough slope and time, and it grows on its own, without any extra pushing from you.' )
			. ls_h( 'Meet Priya and Arjun' )
			. ls_table(
				array( '', 'Priya', 'Arjun' ),
				array(
					array( 'Starts investing at', '22', '35' ),
					array( 'Monthly SIP', '₹2,000', '₹5,000' ),
					array( 'Total invested by 60', 'about ₹9 lakh', 'about ₹15 lakh' ),
					array( 'Value at 60 (12% a year)', '<strong>about ₹1.87 crore</strong>', '<strong>about ₹95 lakh</strong>' ),
				)
			)
			. ls_p( 'Priya puts in less money and ends up with nearly twice as much. Her advantage isn’t income or skill. It’s 13 extra years.' )
			. ls_h( 'Three rules to let compounding work' )
			. ls_list(
				array(
					'<strong>Start early,</strong> even with a small amount.',
					'<strong>Don’t interrupt it.</strong> Withdrawing early resets the snowball.',
					'<strong>Increase slowly.</strong> Raise your SIP with every increment.',
				)
			)
			. ls_p( '<em>The 12% figure is an assumption for illustration. Real returns go up and down and are never guaranteed.</em>' )
			. ls_callout( 'Your one action', 'Plug your own numbers into our <a href="{{url:tools}}#sip">SIP calculator</a> and see what starting this year could mean.' ),
	),

	array(
		'slug'    => 'sip-for-beginners',
		'title'   => 'SIPs for Absolute Beginners: Start Investing with ₹500 a Month',
		'excerpt' => 'A SIP is the gym habit of investing: showing up every month matters more than the size of the first workout.',
		'cats'    => array( 'mutual-funds-sips' ),
		'stages'  => array( 'starting-out' ),
		'order'   => 7,
		'days'    => 30,
		'content' =>
			ls_p( 'A SIP (Systematic Investment Plan) is simply an instruction to invest a fixed amount in a mutual fund every month, automatically. Many funds let you start with ₹500 or even less.' )
			. ls_analogy( '<strong>It’s a gym habit.</strong> Nobody gets fit from one heroic workout. They get fit by turning up every week, including the weeks they don’t feel like it. SIPs work the same way.' )
			. ls_h( 'Why SIPs suit beginners' )
			. ls_list(
				array(
					'<strong>Automatic:</strong> money is invested before you can spend it.',
					'<strong>No timing needed:</strong> you buy every month, in good markets and bad.',
					'<strong>Rupee cost averaging:</strong> when prices fall, the same amount buys more units, which helps over the long run.',
				)
			)
			. ls_h( 'Where many beginners start' )
			. ls_p( 'A low-cost <strong>index fund</strong> is a common first step. It simply owns all the companies in an index, like the Nifty 50, so you don’t have to pick winners. Choose the <strong>direct plan</strong> of a fund to avoid distributor commission, and check the expense ratio: lower is better.' )
			. ls_h( 'Mistakes to avoid' )
			. ls_list(
				array(
					'Stopping your SIP when markets fall. That’s when it buys the most units.',
					'Owning eight funds that all hold the same companies.',
					'Choosing funds based on last year’s returns.',
					'Investing money you’ll need within the next three years.',
				)
			)
			. ls_callout( 'Your one action', 'Once your emergency fund has a month or two of expenses in it, set up your first SIP. Start small and increase it with every increment.' ),
	),

	/* ---------------- Growing ---------------- */

	array(
		'slug'    => 'lifestyle-creep',
		'title'   => 'Your First Appraisal Is a Trap (If You Let It Be)',
		'excerpt' => 'Every raise tempts you to upgrade everything. Here’s a simple rule that lets you enjoy more while still getting richer.',
		'cats'    => array( 'lifestyle-spending' ),
		'stages'  => array( 'growing' ),
		'order'   => 11,
		'days'    => 26,
		'content' =>
			ls_p( 'Your salary grows 10–15% and somehow there’s no extra money at the end of the month. A bigger flat, a better phone, more dinners out. This is <strong>lifestyle creep</strong>: spending rises quietly to match income, and wealth never gets a chance to build.' )
			. ls_analogy( '<strong>It’s like upgrading to a bigger fuel tank and then driving more.</strong> You’re never any further from empty.' )
			. ls_h( 'The 50/50 raise rule' )
			. ls_p( 'Every time your take-home pay rises, send <strong>half of the increase</strong> to savings and investments automatically, and enjoy the other half guilt-free. You still feel richer, and your SIPs grow every year.' )
			. ls_table(
				array( '', 'Before the raise', 'After the raise' ),
				array(
					array( 'Monthly take-home', '₹60,000', '₹70,000' ),
					array( 'Extra to enjoy', '–', '₹5,000' ),
					array( 'Extra to invest (SIP step-up)', '–', '₹5,000' ),
				)
			)
			. ls_h( 'Upgrades worth paying for' )
			. ls_list(
				array(
					'Things that save time or protect health.',
					'Skills that raise your future income.',
					'Experiences with people you care about.',
				)
			)
			. ls_callout( 'Your one action', 'On the day your next increment is credited, increase your SIP by half the raise before you spend any of it.' ),
	),

	array(
		'slug'    => 'circle-of-competence',
		'title'   => 'Your Day Job Is Your Investing Edge: Circle of Competence',
		'excerpt' => 'Warren Buffett’s simplest rule: invest in what you understand. For IT professionals, that’s a bigger advantage than you think.',
		'cats'    => array( 'moats-business-analysis' ),
		'stages'  => array( 'growing' ),
		'order'   => 12,
		'days'    => 6,
		'content' =>
			ls_p( 'Warren Buffett describes his <strong>circle of competence</strong> as the set of businesses he genuinely understands. The size of the circle matters less than knowing where its edges are, and staying inside them.' )
			. ls_analogy( '<strong>You can judge a SaaS product in a week.</strong> Use it, watch how customers react, and you’ll know whether it sticks. You probably can’t do that for a cement plant or a drug pipeline. That difference is your circle.' )
			. ls_h( 'How to map your circle' )
			. ls_list(
				array(
					'<strong>Your industry:</strong> how your company and its clients make money, who the competitors are, what customers complain about.',
					'<strong>Your daily life:</strong> products you use, compare and recommend to friends.',
					'<strong>The test:</strong> could you explain in three sentences how the company makes money and why customers keep coming back? If not, it’s outside your circle for now.',
				)
			)
			. ls_h( 'What the circle is not' )
			. ls_p( 'Knowing a product isn’t the same as knowing the business. A great app can belong to a company that loses money, carries heavy debt or is priced far above its value. Use your edge to ask better questions, then check the numbers.' )
			. ls_callout( 'Your one action', 'Write down three businesses you understand because of your work or daily life. For each one, write the three-sentence test. Keep the list; it’s the start of your watchlist.' ),
	),

	array(
		'slug'    => 'margin-of-safety',
		'title'   => 'Margin of Safety: Why Engineers Build Bridges for Trucks That Never Come',
		'excerpt' => 'Benjamin Graham’s most important idea, explained with a bridge: always leave room to be wrong.',
		'cats'    => array( 'intrinsic-value-margin-of-safety' ),
		'stages'  => array( 'growing' ),
		'order'   => 13,
		'days'    => 16,
		'content' =>
			ls_p( 'Benjamin Graham, Warren Buffett’s teacher, called <strong>margin of safety</strong> the central idea of investing. It means only buying when the price is comfortably below your estimate of what the business is worth.' )
			. ls_analogy( '<strong>If a bridge must carry 10-tonne trucks, engineers build it to carry 30.</strong> Not because they expect 30-tonne trucks, but because their calculations might be wrong. The extra capacity is their margin of safety.' )
			. ls_h( 'How it works with a stock' )
			. ls_p( 'Say you estimate a business is worth ₹100 a share. Buying at ₹98 leaves no room for error. Buying at ₹70 gives you a ₹30 cushion. If you were too optimistic, you are less likely to lose money. If you were right, the returns are better.' )
			. ls_h( 'Why you need it' )
			. ls_list(
				array(
					'Your estimate of value will sometimes be wrong.',
					'The future can surprise even well-run businesses.',
					'Paying too much for a great company can still be a poor investment.',
				)
			)
			. ls_p( 'The same idea works beyond stocks: an emergency fund, adequate insurance and a sensible asset allocation are all margins of safety for your life plan.' )
			. ls_callout( 'Your one action', 'Before your next purchase of any stock, write down what you think it’s worth and why. If the price isn’t clearly below that number, wait.' ),
	),

	/* ---------------- Building Wealth ---------------- */

	array(
		'slug'    => 'portfolio-rebalancing-strategy',
		'title'   => 'Rebalancing Is Value Investing on Autopilot',
		'excerpt' => 'A 60/40 portfolio quietly forces you to buy low and sell high. Here’s how rebalancing works, with real numbers.',
		'cats'    => array( 'rebalancing' ),
		'stages'  => array( 'building-wealth' ),
		'order'   => 21,
		'days'    => 4,
		'content' =>
			ls_p( 'Everyone agrees you should buy low and sell high. Almost nobody does it, because when prices are low the news is scary, and when they’re high everyone is excited. <strong>Rebalancing</strong> takes your emotions out of that decision.' )
			. ls_analogy( '<strong>It’s like a thermostat.</strong> You set the temperature once. When the room drifts, the system corrects it, whatever the weather outside.' )
			. ls_h( 'A worked example' )
			. ls_p( 'You start with ₹10 lakh split 60/40: ₹6 lakh in equity, ₹4 lakh in debt. Then markets fall 30% in a year while debt earns 4%.' )
			. ls_table(
				array( '', 'Equity', 'Debt', 'Mix' ),
				array(
					array( 'Start', '₹6,00,000', '₹4,00,000', '60 / 40' ),
					array( 'After the fall', '₹4,20,000', '₹4,16,000', '50 / 50' ),
					array( 'After rebalancing', '₹5,01,600', '₹3,34,400', '60 / 40' ),
				)
			)
			. ls_p( 'To get back to 60/40, you move <strong>₹81,600 from debt into equity</strong>, buying businesses while they are on sale. When markets later surge, the same rule makes you trim equity and lock in gains.' )
			. ls_h( 'Simple rules that work' )
			. ls_list(
				array(
					'Rebalance once a year, or when your mix drifts more than 5 percentage points.',
					'Use fresh savings to top up the lagging side first; it avoids selling.',
					'Check exit loads and capital gains tax before you sell anything.',
				)
			)
			. ls_callout( 'Your one action', 'Run your own numbers in our <a href="{{url:tools}}#rebalance">60/40 rebalancing calculator</a> and put your next rebalancing date in your calendar.' ),
	),

	array(
		'slug'    => '60-40-portfolio-india',
		'title'   => 'The 40% That Lets You Be Greedy When Others Are Fearful',
		'excerpt' => 'Debt in a 60/40 portfolio isn’t dead money. It’s your war chest for the days Mr. Market panics.',
		'cats'    => array( 'asset-allocation' ),
		'stages'  => array( 'building-wealth' ),
		'order'   => 22,
		'days'    => 11,
		'content' =>
			ls_p( 'Buffett’s famous advice is to be “greedy when others are fearful”. The hard part isn’t the courage. It’s having money available when everything is on sale. That’s the job of the 40% debt side of a 60/40 portfolio.' )
			. ls_analogy( '<strong>Debt is the spare tyre and the toolkit in your car boot.</strong> You don’t think about it on a smooth highway, but it’s what keeps you moving when something goes wrong.' )
			. ls_h( 'What the 40% does for you' )
			. ls_list(
				array(
					'<strong>Steadies the ride,</strong> so a market crash doesn’t become a personal crisis.',
					'<strong>Funds near-term goals</strong> without forcing you to sell shares at bad prices.',
					'<strong>Becomes buying power</strong> when you rebalance after a fall.',
				)
			)
			. ls_h( 'What can count as debt' )
			. ls_p( 'Your EPF and PPF balances, fixed deposits, and debt mutual funds all count. Many people forget their EPF and end up with more debt than they think, so add it in when you check your mix.' )
			. ls_h( 'Is 60/40 right for you?' )
			. ls_p( 'It’s a sensible middle path, not a law. Younger investors with stable jobs often hold more equity; people close to a big goal or retirement often hold more debt. The best allocation is one you can stick with through a bad year.' )
			. ls_callout( 'Your one action', 'List every investment you own, including EPF and PPF, and work out your real equity-to-debt split today.' ),
	),

	array(
		'slug'    => 'economic-moats',
		'title'   => 'Why You Never Leave WhatsApp: A Plain-English Guide to Economic Moats',
		'excerpt' => 'Great long-term investments have defences competitors can’t easily cross. Here are the five kinds of moat and how to spot one that’s drying up.',
		'cats'    => array( 'moats-business-analysis' ),
		'stages'  => array( 'building-wealth' ),
		'order'   => 23,
		'days'    => 21,
		'content' =>
			ls_p( 'Warren Buffett looks for businesses with a wide <strong>economic moat</strong>: a lasting advantage that keeps competitors out, the way a moat protects a castle. Without one, high profits attract rivals until the profits disappear.' )
			. ls_analogy( '<strong>Better-designed messaging apps exist. You still use WhatsApp</strong>, because everyone you know is there. That’s a network-effect moat. A delivery app whose only advantage is discount coupons has no moat: when the coupons stop, so do the customers.' )
			. ls_h( 'The five common moats' )
			. ls_table(
				array( 'Moat', 'What it means', 'Everyday example' ),
				array(
					array( 'Network effects', 'The product gets better as more people use it', 'Messaging apps, marketplaces' ),
					array( 'Switching costs', 'Leaving is painful, slow or risky', 'Your salary bank account, enterprise software' ),
					array( 'Brand', 'People pay more or choose it without thinking', 'The paint or biscuit brand your family always buys' ),
					array( 'Cost advantage', 'It can sell cheaper and still profit', 'The largest, most efficient producer in an industry' ),
					array( 'Licences and regulation', 'Few are legally allowed to compete', 'Exchanges, some utilities' ),
				)
			)
			. ls_h( 'Signs a moat is drying up' )
			. ls_list(
				array(
					'Prices have to be cut to keep customers.',
					'A new technology makes switching easy.',
					'Profit margins shrink year after year.',
				)
			)
			. ls_p( '<em>Examples here are case studies to explain a concept, not recommendations to buy or sell anything.</em>' )
			. ls_callout( 'Your one action', 'For each stock or fund holding you understand, write one sentence describing its moat. If you can’t, that’s worth investigating.' ),
	),
);
