@php($tinyKey = config('services.tiny.api_key', 'no-api-key'))
<script src="https://cdn.tiny.cloud/1/{{ $tinyKey }}/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script>
  tinymce.init({
    selector: 'textarea.tinymce-editor',
    plugins: 'code table lists autolink link fullscreen autoresize',
    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | indent outdent | bullist numlist | table | link | code | fullscreen | print',
    menubar: false,
    height: 600,
    resize: true
  });
</script>
