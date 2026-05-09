<?php

require_once __DIR__ . '/../includes/bootstrap.php';

sn_require_login();

sn_render_shell_start('About', ['nav' => true]);

?>
        <h1 class="sn-page-title">About</h1>
        <p class="sn-page-lead">Course submission details.</p>

        <div class="sn-card">
            <dl class="sn-dl">
                <div>
                    <dt>Student name</dt>
                    <dd><?php echo sn_e('Nguyen Dinh Toan Thang'); ?></dd>
                </div>
                <div>
                    <dt>Student number</dt>
                    <dd><?php echo sn_e('1694559'); ?></dd>
                </div>
            </dl>
        </div>
<?php
sn_render_shell_end();
