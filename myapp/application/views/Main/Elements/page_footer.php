    <div id="footer" role="contentinfo">
        <div class="container-fluid">
            <div class="row">
                <div class="col text-center">
                    <p>
                        <?php echo anchor('main', 'Copyright');?> &copy; <?php echo date("Y"); ?> All Rights.
                        <a href="##" id="top-of-page" class="pull-right">Back to top <i class="icon-chevron-up"></i></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="/assets/js/main.js"></script>

    <?php if (isset($js_to_load) && is_array($js_to_load)) : ?>
    <?php foreach ($js_to_load as $row): ?>
        <script type="text/javascript" src="<?php echo $row;?>"></script>
    <?php endforeach;?>
    <?php endif;?>

<?php
    $caller_class = $this->router->class;
    $caller_method = $this->router->fetch_method();
    //look for JS for the class/controller
    $class_js = "/assets/js/custom-".$caller_class.".js";
    if(file_exists(getcwd()."/".$class_js)){
        ?><script type="text/javascript" src="<?php echo base_url().$class_js; ?>"></script>
        <?php
    }

    //look for JS for the class/controller/method
    $class_method_js = "/assets/js/custom-".$caller_class."-".$caller_method.".js";
    if(file_exists(getcwd()."/".$class_method_js)){
        ?><script type="text/javascript" src="<?php echo base_url().$class_method_js; ?>"></script>
        <?php
    }
?>
</body>
</html>