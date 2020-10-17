function adminPageEditorOptionClick(a) {
    let edit = a.value;
    let editorMainPage = document.getElementById('editorMainPage');
    let editorPrivate = document.getElementById('editorPrivate');
    let editorCoordFiles = document.getElementById('editorCoordFiles');
    let editorPartTestPage = document.getElementById('editorPartTestPage');
    let editorPartCreativeWord = document.getElementById('editorPartCreativeWord');

    if (edit == 1) {
        editorMainPage.style.display = 'block';
        editorPrivate.style.display = 'none';
        editorCoordFiles.style.display = 'none';
        editorPartTestPage.style.display = 'none';
        editorPartCreativeWord.style.display = 'none';
    } else if (edit == 2) {
        editorMainPage.style.display = 'none';
        editorPrivate.style.display = 'block';
        editorCoordFiles.style.display = 'none';
        editorPartTestPage.style.display = 'none';
        editorPartCreativeWord.style.display = 'none';
    } else if (edit == 3) {
        editorMainPage.style.display = 'none';
        editorPrivate.style.display = 'none';
        editorCoordFiles.style.display = 'block';
        editorPartTestPage.style.display = 'none';
        editorPartCreativeWord.style.display = 'none';
    } else if (edit == 4) {
        editorMainPage.style.display = 'none';
        editorPrivate.style.display = 'none';
        editorCoordFiles.style.display = 'none';
        editorPartTestPage.style.display = 'block';
        editorPartCreativeWord.style.display = 'none';
    } else if (edit == 5) {
        editorMainPage.style.display = 'none';
        editorPrivate.style.display = 'none';
        editorCoordFiles.style.display = 'none';
        editorPartTestPage.style.display = 'none';
        editorPartCreativeWord.style.display = 'block';
    }
}

function savePageEditorClick() {
    //HEADER
    //фото
    let editorNewLppLogo =  document.getElementById('editorNewLppLogo').files[0];
    let editorPartnerLogo = document.getElementById('editorPartnerLogo').files[0];

    let editorAboutInfoH2 = document.getElementById('editorAboutInfoH2').value;
    let editorAboutInfoH1 = document.getElementById('editorAboutInfoH1').value;

    let editorAboutInfoPhone = document.getElementById('editorAboutInfoPhone').value;
    let editorAboutInfoEmail = document.getElementById('editorAboutInfoEmail').value;

    let checkbox = document.getElementById('editorActivCoordReg');
    if (checkbox.checked == true) checkbox = 'true';
    else checkbox = 'false';

    let EditorPrivateTextPage = $('#EditorPrivateTextPage').summernote('code');
    let EditorForStartTestPage = $('#EditorForStartTestPage').summernote('code');
    let EditorForCreativWorkPage = $('#EditorForCreativWorkPage').summernote('code');

    let data = new FormData();
    data.append('LppLogo',editorNewLppLogo);
    data.append('PartnerLogo',editorPartnerLogo);
    data.append('InfoH2',editorAboutInfoH2);
    data.append('InfoH1',editorAboutInfoH1);
    data.append('InfoPhone',editorAboutInfoPhone);
    data.append('InfoEmail',editorAboutInfoEmail);
    data.append('PrivateTextPage',EditorPrivateTextPage);
    data.append('StartTestPage',EditorForStartTestPage);
    data.append('CreativWorkPage',EditorForCreativWorkPage);
    data.append('checkbox',checkbox);

    fetch('http://lpptourism.ru/core/adminzone/editor/loadNewEdits.php',{
        method: 'POST',
        body: data
    })
    .then(response => response.text())
    .then(result => {
        document.getElementById('errDivClickForEditorPages').innerHTML = result;
    });
}
/*
function dataLoaderForEditor() {
    fetch('http://lpptourism.ru/core/adminzone/editor/dataLoaderForEditor.php')
    .then(response => response.json())
    .then(result => {

    document.getElementById('editorAboutInfoH2').value = result[`InfoH2`];
    document.getElementById('editorAboutInfoH1').value = result[`InfoH1`];

    document.getElementById('editorAboutInfoPhone').value = result[`InfoPhone`];
    document.getElementById('editorAboutInfoEmail').value = result[`InfoEmail`];

    if (result[`checkboxForCoordReg`] == 'true') document.getElementById('editorActivCoordReg').checked = true;
    else document.getElementById('editorActivCoordReg').checked = false;

    let PrivateTextPage = result[`PrivateTextPage`];
    let StartTestPage = result[`StartTestPage`];
    let CreativWorkPage = result[`CreativWorkPage`];

    document.getElementById('EditorPrivateTextPage').innerHTML = result[`PrivateTextPage`];
    document.getElementById('EditorForStartTestPage').innerHTML = result[`StartTestPage`];
    document.getElementById('EditorForCreativWorkPage').innerHTML = result[`CreativWorkPage`];

    //$('#EditorPrivateTextPage').summernote('editor.insertText',PrivateTextPage);
    //$('#EditorForStartTestPage').summernote('editor.insertText',StartTestPage);
    //$('#EditorForCreativWorkPage').summernote('editor.insertText',CreativWorkPage);
    summerDescEdit = document.getElementsByClassName('editorTextArea');
    for (let i = 0; i < summerDescEdit.length; i++) {
            $('.editorTextArea').eq(i).summernote({
                lang: 'ru-RU',
                minHeight: 100,
                maxHeight: 350,
                height: 250, // set editor height
                focus: true, // set focus to editable area after initializing summernote
                disableDragAndDrop: true,
                placeholder: 'Текст'
            });
            $('.editorTextAreaBig').eq(i).summernote({
                lang: 'ru-RU',
                minHeight: 350,
                maxHeight: 550,
                height: 450, // set editor height
                focus: true, // set focus to editable area after initializing summernote
                disableDragAndDrop: true,
                placeholder: 'Текст'
            });
        }

    document.getElementById('headerLogoImg').src = result[`logoURL_bd`];
    document.getElementById('headerPartnerImg').src = result[`PartnerLogoURL_bd`];
  });
}
*/
function imgToLabelsLChange(e) {
    var img = e.currentTarget.getElementsByTagName("img")[0];
    var files = e.currentTarget.getElementsByTagName('input')[0].files[0];
    var reader = new FileReader();

    reader.onloadend = function () {
        img.src = reader.result;
    };
    if (files) {
        reader.readAsDataURL(files);
    }

}
