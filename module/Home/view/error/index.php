<?php $DevelopmentMode = false; ?>

<h1>An error occurred</h1>
<h2><?= $this->message ?></h2>

<?php
if (isset($this->display_exceptions) && $this->display_exceptions):
	if(isset($this->exception) && ($this->exception instanceof Exception || $this->exception instanceof Error)):
?>
		<hr />
		<h2>Additional information:</h2>
		<h3><?= get_class($this->exception) ?></h3>
		<dl>
<?php
		if ($DevelopmentMode):
?>
			<dt>File:</dt>
			<dd>
				<pre class="prettyprint linenums"><?= $this->exception->getFile() ?>:<?= $this->exception->getLine() ?></pre>
			</dd>
			<dt>Message:</dt>
			<dd>
				<pre class="prettyprint linenums"><?= $this->escapeHtml($this->exception->getMessage()) ?></pre>
			</dd>
			<dt>Stack trace:</dt>
			<dd>
				<pre class="prettyprint linenums"><?= $this->escapeHtml($this->exception->getTraceAsString()) ?></pre>
			</dd>
<?php
		else:
?>
			<dt>Message:</dt>
			<dd>
				<pre class="prettyprint linenums"><?= $this->escapeHtml($this->exception->getMessage()) ?></pre>
			</dd>
<?php
		endif;
?>
		</dl>
<?php
		

		$e = $this->exception->getPrevious();
		$icount = 0;
		if ($e) :
?>
			<hr />
			<h2>Previous exceptions:</h2>
			<ul class="unstyled">
<?php
			while($e) :
?>
				<li>
					<h3><?= get_class($e) ?></h3>
					<dl>
<?php
				if ($DevelopmentMode):
?>
						<dt>File:</dt>
						<dd>
							<pre class="prettyprint linenums"><?= $e->getFile() ?>:<?= $e->getLine() ?></pre>
						</dd>
						<dt>Message:</dt>
						<dd>
							<pre class="prettyprint linenums"><?= $this->escapeHtml($e->getMessage()) ?></pre>
						</dd>
						<dt>Stack trace:</dt>
						<dd>
							<pre class="prettyprint linenums"><?= $this->escapeHtml($e->getTraceAsString()) ?></pre>
						</dd>
<?php
				else:
?>
						<dt>Message:</dt>
						<dd>
							<pre class="prettyprint linenums"><?= $this->escapeHtml($e->getMessage()) ?></pre>
						</dd>
					</dl>
<?php
				endif;
?>
				</li>
<?php
				$e = $e->getPrevious();
				$icount += 1;
				if ($icount >= 50)
				{
					echo "<li>There may be more exceptions, but we have no enough memory to proccess it.</li>";
					break;
				}
			endwhile;
?>
			</ul>
<?php
		endif;
	else:
?>
		<h3>No Exception available</h3>
<?php
	endif;
endif;
?>
