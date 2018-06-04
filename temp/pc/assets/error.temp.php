
<body id="error_body">
    <div id="site_error_wrap">

        <div id="errormsg"><?php print $message; ?></div>

        <?php if ($details){ ?>
                <div class="pre"><?php print nl2br($details); ?></div>
        <?php } ?>

        <?php $stack = debug_backtrace(); ?>
        <?php if(!isset($stack[4])){ return; } ?>

        <p><b><?php //print LANG_TRACE_STACK; ?>:</b></p>

        <ul id="trace_stack">

            <?php for($i=4; $i<=14; $i++){ ?>

                <?php if (!isset($stack[$i])){ break; } ?>

                <?php $row = $stack[$i]; ?>
                <li>
                    <b>
                        <?php if (isset($row['class'])) { ?>
                            <?php print $row['class'] . $row['type'] . $row['function'] . '()'; ?>
                        <?php } else { ?>
                            <?php print $row['function'] . '()'; ?>
                        <?php } ?>
                    </b>
                    <?php if (isset($row['file'])) { ?>
                        <span>@ <?php print str_replace(ROOT, '/', $row['file']); ?></span> : <span><?php print $row['line']; ?></span>
                    <?php } ?>
                </li>

            <?php } ?>

        </ul>

    </div>
</body>