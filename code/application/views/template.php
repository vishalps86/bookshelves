<?php
if (!$this->input->is_ajax_request()) {
    $this->load->view('templates/header');
    $this->load->view('templates/leftmenu');
}
?>
<?php if (!$this->input->is_ajax_request()) { ?>

<div class="static-content-wrapper region-content">
<?php } ?>

<?php $this->load->view($v);?>

<?php if (!$this->input->is_ajax_request()) { ?>
  </div>
<?php } ?>

<?php
if (!$this->input->is_ajax_request()) {
  $this->load->view('templates/footer');
}
?>
