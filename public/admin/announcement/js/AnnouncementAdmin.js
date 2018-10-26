$.ajax({
	url: 'resources/announcements.json',
	contentType: 'application/json; charset=utf-8',
	dataType: 'text json',
	success: function(response){
		var announcementTable = $("#announcement-table");
		announcementTable.append(
			'<thead>'
			+   '<tr>'
			+       '<th>Título</th>'
			+       '<th>Autor</th>'    
			+       '<th>Fecha</th>'
			+   '</tr>'
			+'</thead>'
			+'<tbody>'
		)
		$.each(response, function(index, announcement) {
			announcementTable.append(
				'<tr>'
				+     '<td>' + announcement.title + '</td>'
				+     '<td>' + announcement.author + '</td>'
				+     '<td>' + announcement.timestamp + '</td>'
				+       '<td><a class=\'fa fa-eye\'></a></td>'
				+       '<td><a class=\'fa fa-pencil-alt\'></a></td>'
				+       '<td><a class=\'fa fa-trash\'></a></td>'
				+ '</tr>'
			);
		});
		announcementTable.append('</tbody>')
		console.log(announcementTable)
	},
	error: function(error){
		console.error(error);
	}
}); 