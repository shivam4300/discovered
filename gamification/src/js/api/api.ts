
// @ts-check

export const METHOD_GET = 'GET';
export const METHOD_POST = 'POST';
export const METHOD_JSON = 'JSON';

export function getJSON<T>(url:string, data:Record<string, any> = {}, type = METHOD_GET, headers:[string, string][] = []):Promise<T> {
	return new Promise((resolve, reject) => {
		if (!url) {
			console.error('No url specified');
			reject();
			return;
		}

		let append = '';
		if (type === METHOD_GET && data) {
			append += url.toString().indexOf('?') >= 0 ? '&' : '?';

			let str = '';
			for (const key in data) {
				if (str !== '') {
					str += '&';
				}
				str += key + '=' + encodeURIComponent(data[key]);
			}
			append += str;
		}

		const request = new XMLHttpRequest();
		const method = type === METHOD_JSON ? METHOD_POST : type;
		request.open(method, url + append, true);

		let postData;
		if (type === METHOD_POST) {
			postData = Object.keys(data).reduce((fd, k) => {
				fd.append(k, data[k] === null ? '' : data[k]);
				return fd;
			}, new FormData());
		} else if (type === METHOD_JSON) {
			request.setRequestHeader('Content-Type', 'application/json');
			postData = JSON.stringify(data || '');
		}

		// @ts-ignore
		headers.forEach(header => request.setRequestHeader(...header));

		request.onerror = (e) => {
			console.error(e);
		};
		request.onload = () => {
			if (request.status >= 200 && request.status < 400) {
				const res = JSON.parse(request.responseText);
				const receivedData = res;
				resolve(receivedData);
			} else {
				reject();
			}
		};
		request.onerror = reject;
		request.send(postData);
	});
}

