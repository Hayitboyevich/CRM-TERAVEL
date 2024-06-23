<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8"/>
    <base href='../'/>
</head>
<body>
<div>

    <div class="hs-docs-content-divider">
        <link rel="stylesheet" href="{{ asset('rte_theme_default.css') }}"/>
        <script type="text/javascript" src="{{ asset('rte.js') }}"></script>
        <script>RTE_DefaultConfig.url_base = 'richtexteditor'</script>
        <script type="text/javascript" src='{{ asset('all_plugins.js') }}'></script>


        <form id="save-data-form" method="POST" action="{{ route('applications.download') }}">
            @csrf
            <input type="hidden" name="data" id="data">
            <div id="div_editor1">{!! $html_data !!}</div>
            <button style="
                  display: block;
                  width: 100%;
                  border: none;
                  background-color: #04AA6D;
                  padding: 14px 28px;
                  font-size: 16px;
                  cursor: pointer;
                  text-align: center;
                  color:white;
                  border-radius: 5px;
                  margin-top: 10px;
                  margin-bottom: 10px;"
                    onclick="setChanges()" id="save-form">@lang('app.download')
            </button>
        </form>

        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script>
            // set thee editor changes to the hidden input
            function setChanges() {
                $('#data').val(editor1.getHTMLCode());
            }

            var editor1 = new RichTextEditor("#div_editor1");

            // editor1.setHTMLCode("<p><b>Hello</b> World</p><p>Click the button below to show this HTML code</p>");


            function btngetHTMLCode() {
                alert(editor1.getHTMLCode())
            }

            function btnsetHTMLCode() {
                editor1.setHTMLCode("<h1>editor1.setHTMLCode() sample</h1><p>You clicked the setHTMLCode button at " + new Date() + "</p>")
            }

            function btngetPlainText() {
                alert(editor1.getPlainText())
            }

        </script>


    </div>

</div>
<script src="{{ asset('patch.js') }}"></script>
</body>
</html>
