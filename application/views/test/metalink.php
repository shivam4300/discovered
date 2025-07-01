<script type="text/javascript" src="https://test.discovered.tv/repo/js/jquery.js"></script>

<input type="text" placeholder="Type URL here" value="http://www.html5rocks.com/en/tutorials/cors/" />
<button>Get Meta Data</button>


<script>

$('button').click(function() {
	let url = $('input').val();
	Fetch(url).
	then(function(html){
		let data = getMetaData(html);
		console.log(data);
	}).catch(function(e){
		url = url.replace("https", "http");
		Fetch(url).
		then(function(html){
			let data = getMetaData(html);
			console.log('fail',e);
		}).catch(function(e){
			console.log(data);
		})
	})
	
});
function getMetaData(html){
	var rich = ['description','Description','keywords','Keywords','image','src','title'];
	var datas = [];
	for(i=0;i < rich.length;i++){
		if(rich[i] == 'src')
		datas[rich[i]] = html.find('img').attr('src') || false;
		else
		datas[rich[i]] = getMetaContent(html, rich[i] ) || false
		
		if((rich.length - 1) == i){
			return datas;
		}
	}
}
function Fetch(url){
	return new Promise(function(resolve, reject) {
		$.ajax({
			url: 'https://cors-anywhere.herokuapp.com/' + url
		}).done(function(html){
			var html = $(html);
			return resolve(html);
		}).fail(function(e){
			return reject(e);
		})
	});
}

function getMetaContent(html, name) {
  return html.filter(
  (index, tag) => tag && tag.name && tag.name == name).attr('content');
}

function urlify(text) {
  var urlRegex = /(https?:\/\/[^\s]+)/g;
  return text.replace(urlRegex, function(url) {
    return '<a href="' + url + '">' + url + '</a>';
  })
}


// var html = urlify(text);
</script>