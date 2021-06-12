<html>

<body>
	<textarea id="content" style="width: 800px; height: 300px; margin-left: auto; margin-right: auto;"></textarea>
	<br>
	<button onclick="updateView()" style="width: 200px; margin-left: auto; margin-right: auto; padding: 5px 25px; background-color: powderblue; cursor: pointer;">Update View</button>
	<br><br>
	Pack <input id="pack" style="width: 400px;"> <br><br>
	Exam <input id="exam" style="width: 400px;"> <br><br>
	<div id="content_view" style="max-height: 200px; overflow-y: hidden;">

	</div>
	<br>
	<button onclick="uploadData()" style="width: 200px; margin-left: auto; margin-right: auto; padding: 5px 25px; background-color: powderblue; cursor: pointer;">Upload</button>
</body>
<script>
	function postRequest(link, data, func) {
		var http = new XMLHttpRequest();
		http.open('POST', link, true);
		http.onreadystatechange = function() {
			if (http.readyState == 4 && http.status == 200) {
				func(http.responseText);
			}
		}
		http.send(data);
	}

	function updateView() {
		document.getElementById('content_view').innerHTML = document.getElementById('content').value;
	}

	function uploadData() {
		var pack = document.getElementById('pack').value;
		var exam = document.getElementById('exam').value;
		var jsonData;
		var data = {};
		var dataList = document.getElementsByClassName("row content ndcauhoi");
		for (var i = 0; i < dataList.length; i++) {
			var temp = {};
			temp['ques'] = dataList[i].children[0].children[1].children[0].innerText;
			temp['important'] = '0';
			if (temp['ques'].substring(0, 2) == "* ") {
				temp['important'] = '1';
				temp['ques'] = temp['ques'].substring(2);
			}
			var img = dataList[i].children[0].children[2].src.split("/");
			temp['ques_image'] = img[img.length - 1];
			if (temp['ques_image'] == "0.jpg") temp['ques_image'] = "";
			else{
				temp['ques_image_url'] = "https://hoclaixemoto.com/image600/" + temp['ques_image'];
			}
			var ansList = dataList[i].getElementsByClassName("cautraloi");
			temp['ans1'] = '';
			temp['ans2'] = '';
			temp['ans3'] = '';
			temp['ans4'] = '';
			for (var j = 0; j < ansList.length; j++) {
				temp['ans' + (j + 1)] = ansList[j].children[0].children[0].value;
				if (ansList[j].children[0].className == "checkbox-inline mauxanh") temp['correct_ans'] = '' + (j + 1);
			}
			// temp['detail'] = dataList[i].children[2].children[1].innerText;
			temp['detail'] = "";
			if (i < 17) temp['type'] = 'luat';
			else if (i < 27) temp['type'] = 'bienbao';
			else temp['type'] = 'sahinh';
			data['' + i] = temp;
		}
		jsonData = JSON.stringify(data);
		console.log(data);
		var fd = new FormData();
		fd.append('pack', pack);
		fd.append('exam', exam);
		fd.append('ques_data', jsonData);
		postRequest("?action=upload_data", fd, function(resp) {
			console.log(resp);
		});
	}
</script>

</html>