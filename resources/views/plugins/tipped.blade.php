{!! HTML::style('plugins/tipped/tipped.css') !!}
{!! HTML::script('plugins/tipped/tipped.js') !!}

<script type="text/javascript">
  $(document).ready(function() {
    Tipped.create('.tipped-tooltip', {size: 'x-small', position: 'bottomleft'});
  });
</script>