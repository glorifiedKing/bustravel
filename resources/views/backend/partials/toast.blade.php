
@if (session('bustravel-flash'))
<script>
    var info_type = {!! json_encode(session('bustravel-flash-type')) !!} || 'info';
    var info_class = 'bg-info';
    var info_message = {!! json_encode(session('bustravel-flash-message')) !!} || 'information';
    var info_title = {!! json_encode(session('bustravel-flash-title')) !!} || 'Information';
    var info_subtitle = {!! json_encode(session('bustravel-flash-subtitle')) !!} || '';
    switch(info_type) {
        case 'info':
            info_class = 'bg-info';
            break;
        case 'success':
            info_class = 'bg-success';
            break;
        case 'error':
            info_class = 'bg-danger';
            break;  
        case 'warning':
            info_class = 'bg-warning';
            break;       
        default:
            info_class = 'big-info';
    }
    $(document).Toasts('create', {
        class: info_class, 
        title: info_title,
        subtitle: info_subtitle,
        body: info_message
      })
</script>
@endif