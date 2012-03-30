//...Moreの上にカーソルが来たらコメントをすべて表示させるスクリプト
function showComment(elem)
{
	if(!document.createAttribute) return;
	var comment = elem.lastChild;
	if(comment.style.display == 'none')
		comment.style.display = 'block';
	else
		comment.style.display = 'none';
}