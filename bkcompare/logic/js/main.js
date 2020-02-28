document.addEventListener('DOMContentLoaded',
    function () {

    if(document.forms.creteComp){
        document.forms.creteComp.addEventListener('submit', createComp);

        function createComp(event) {
            event.preventDefault();

            var formData = new FormData(this);

            doAjax({
                method: 'POST',
                url: 'logic/'
            })
        }
    }




    });