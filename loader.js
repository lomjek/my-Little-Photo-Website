/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

async function loadfolders() {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const responseText = xhr.responseText;
                var lines = responseText.split("\n");
                lines.forEach(line => {
                    console.log(line);
                    var arr = line.split(":");
                    if (arr[1] != undefined){
                        display(arr[0], arr[1], arr[2], arr[3], arr[4]);
                    };
                });                

            } else {
                console.error('Request failed with status:', xhr.status);
            }
        }
    };

    xhr.open('POST', 'loading.php', true);
    xhr.send();
}

function display(folder, jpgcnt, date, bcolor, tcolor){
    // Create a table element
    var table = document.createElement('table');

    // Create table cells (td) and their contents
    var cell1 = document.createElement('td');
    var heading1 = document.createElement('h2');
    heading1.textContent = folder.split("-").join(" ");
    heading1.classList.add('inside');
    cell1.appendChild(heading1);

    var cell2 = document.createElement('td');
    var heading2 = document.createElement('h3');
    heading2.textContent = date;
    heading2.classList.add('inside');
    cell2.appendChild(heading2);

    var cell3 = document.createElement('td');
    var heading3 = document.createElement('h4');
    heading3.textContent = jpgcnt + ' Photos';
    heading3.classList.add('inside');
    cell3.appendChild(heading3);

    cell1.style.color = tcolor;
    cell2.style.color = tcolor;
    cell3.style.color = tcolor;

    // Append cells to the row
    table.appendChild(cell1);
    if (date != "") {
        table.appendChild(cell2);
    }
    table.appendChild(cell3);

    table.style.backgroundColor = bcolor;

    var link = document.createElement('a');
    link.href = 'photos/' + folder + '/';
    link.appendChild(table);

    document.body.appendChild(link);
    document.body.appendChild(document.createElement('br'));

    document.getElementById("LoadingIndicator").style.display = "none";
}
loadfolders();
  
  

