<p>Hello <?= esc($name) ?>,</p>
<p>Thanks for registering for APER. Please verify your email by clicking the link below:</p>
<p><a href="<?= site_url('verify/' . $token) ?>"><?= site_url('verify/' . $token) ?></a></p>
<p>If you did not sign up, ignore this message.</p>
<p>— APER Team</p>
