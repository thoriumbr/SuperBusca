function changeSetting(e) {
			const optionName = e.name;
			var optionValue = "";

			if (e.className == 'bool') {
				optionValue = e.checked;
				document.getElementById(e.name).checked = e.checked;
			} 
			if (e.className == 'text') {
				optionValue = String(e.value).replaceAll('\n', '_');
				document.getElementById(e.name).value = e.value;
			}
			if (e.className == 'int') {
				var numbers = /^[0-9]+$/;
				if (!e.value.match(numbers))
					return false;
			}

			var request = new XMLHttpRequest();
			request.open('GET', 'options.php?change=' + optionName + ':' + optionValue, true);
			request.onload = function() {
				if (this.status >= 200 && this.status < 400) {
					// Success!
					console.log(this.response);
				} else {
					// We reached our target server, but it returned an error

				}
			};

			request.onerror = function() {
				// There was a connection error of some sort
			};

			request.send();
		}

		function doSearch() {
			phrase = searchPhrase.value;
			var request = new XMLHttpRequest();
			request.open('GET', 'search.php?q=' + phrase, true);

			request.onload = function() {
				if (this.status >= 200 && this.status < 400) {
					// Success!
					var data = JSON.parse(this.response);
					hits = data.hits.hits.length;
					searchResult.innerHTML = "Hits: " + hits + "<br><br>"; //data.hits.hits[0]._source.data.replace(/(?:\r\n|\r|\n)/g, '<br>');
					for (i = 0; i < hits; i++) {
						textFile = data.hits.hits[i]._source.filename;
						imageFile = textFile.replace(/txt/g, 'jpg');
						searchResult.innerHTML = searchResult.innerHTML +
							"<span class='resultEntry'>" +
							"<a href='images/" + imageFile + "' data-featherlight='images/" + imageFile + "'>" +
							"<img src='thumb.php?src=images/" + imageFile + "&size=<200'><br>" +
							"<a href='text.php?path=" + textFile + "' data-featherlight='text.php?path=" + textFile + "'>Text file</a>" +
							"</span>";
					}
					var lightbox = $('.searchResult a').simpleLightbox({});
					//console.log(data)
				} else {
					// We reached our target server, but it returned an error

				}
			};

			request.onerror = function() {
				// There was a connection error of some sort
			};

			request.send();
		}

		function showSettings() {
			document.getElementById('config').style.visibility = 'visible';
		}

		function hideSettings() {
			document.getElementById('config').style.visibility = 'hidden';
		}