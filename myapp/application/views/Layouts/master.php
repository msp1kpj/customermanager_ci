<?php $this->load->view('Main/Elements/page_header'); ?>
<?php $this->load->view('Main/Elements/navbar'); ?>
<div class="container-fluid">
    <?php 
    if(isset($content)){
        $this->load->view($content); 
    }
    ?>
</div>
<?php $this->load->view('Main/Elements/page_footer'); ?>