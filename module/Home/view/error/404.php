<?php

use Zend\Mvc\Application;

?>
    <h1>A 404 error occurred</h1>
    <h2><?= $this->message ?></h2>

<?php if (!empty($this->reason)) :
    switch ($this->reason) {
        case Application::ERROR_CONTROLLER_CANNOT_DISPATCH:
            $reasonMessage = 'The requested controller was unable to dispatch the request.';
            break;
        case Application::ERROR_MIDDLEWARE_CANNOT_DISPATCH:
            $reasonMessage = 'The requested middleware was unable to dispatch the request.';
            break;
        case Application::ERROR_CONTROLLER_NOT_FOUND:
            $reasonMessage = 'The requested controller could not be mapped to an existing controller class.';
            break;
        case Application::ERROR_CONTROLLER_INVALID:
            $reasonMessage = 'The requested controller was not dispatchable.';
            break;
        case Application::ERROR_ROUTER_NO_MATCH:
            $reasonMessage = 'The requested URL could not be matched by routing.';
            break;
        default:
            $reasonMessage = 'We cannot determine at this time why a 404 was generated.';
            break;
    }
    ?>
    <p><?= $reasonMessage ?></p>
<?php endif ?>

<?php if (!empty($this->controller)) : ?>
    <dl>
        <dt>Controller:</dt>
        <dd>
            <?= $this->escapeHtml($this->controller) ?>
            <?php
            if (!empty($this->controller_class) && $this->controller_class != $this->controller) {
                printf('(resolves to %s)', $this->escapeHtml($this->controller_class));
            }
            ?>
        </dd>
    </dl>
<?php endif ?>

<?php if (!empty($this->display_exceptions)) : ?>
    <?php if (isset($this->exception)
        && ($this->exception instanceof \Exception || $this->exception instanceof \Error)) : ?>
        <hr/>

        <h2>Additional information:</h2>
        <h3><?= get_class($this->exception) ?></h3>
        <dl>
            <dt>File:</dt>
            <dd>
                <pre><?= $this->exception->getFile() ?>:<?= $this->exception->getLine() ?></pre>
            </dd>
            <dt>Message:</dt>
            <dd>
                <pre><?= $this->escapeHtml($this->exception->getMessage()) ?></pre>
            </dd>
            <dt>Stack trace:</dt>
            <dd>
                <pre><?= $this->escapeHtml($this->exception->getTraceAsString()) ?></pre>
            </dd>
        </dl>

        <?php if ($ex = $this->exception->getPrevious()) : ?>
            <hr/>

            <h2>Previous exceptions:</h2>
            <ul class="list-unstyled">
                <?php $icount = 0 ?>
                <?php while ($ex) : ?>
                    <li>
                        <h3><?= get_class($ex) ?></h3>
                        <dl>
                            <dt>File:</dt>
                            <dd>
                                <pre><?= $ex->getFile() ?>:<?= $ex->getLine() ?></pre>
                            </dd>
                            <dt>Message:</dt>
                            <dd>
                                <pre><?= $this->escapeHtml($ex->getMessage()) ?></pre>
                            </dd>
                            <dt>Stack trace:</dt>
                            <dd>
                                <pre><?= $this->escapeHtml($ex->getTraceAsString()) ?></pre>
                            </dd>
                        </dl>
                    </li>
                    <?php
                    $ex = $ex->getPrevious();
                    if (++$icount >= 50) {
                        echo '<li>There may be more exceptions, but we do not have enough memory to process it.</li>';
                        break;
                    }
                    ?>
                <?php endwhile ?>
            </ul>
        <?php endif ?>
    <?php else : ?>
        <h3>No Exception available</h3>
    <?php endif ?>
<?php endif ?>