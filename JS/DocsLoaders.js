let LoaderDocsMainPage = function(page) {
    fetch('http://lpptourism.ru/core/adminzone/files/listOfFilesMainPage.php')
        .then(response => response.json())
        .then(res => {
            let i = 0;
            let links = res[`links`];
            let names = res[`names`];
            let html = '';
            while (i < links.length) {
                if (page == 'admin') {
                    html = html + "<div><a href='" + links[i] + "'>" + names[i] + "</a><button value='" + links[i] + "' class='dellDocButton' onclick='dellDocsMainPage(this)'>X</button></div>";
                } else {
                    html = html + "<div><a href='" + links[i] + "'>" + names[i] + "</a></div>";
                }
                i++;
            }
            document.getElementById('LoaderDocsMainPage').innerHTML = html;
        });
};
