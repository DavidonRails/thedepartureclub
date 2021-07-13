<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <title>HoboHet</title>
		<style type="text/css">
			body, html{height:100%}
			.header {
				height: 70px;
			}
			.header > td {
				border-bottom: 1px solid #F1F1F1;
			}
			.header td table {
				width: 100%;
				max-width: 550px;
				margin: 0 auto;
			}
			.header td table td {
				padding-top: 10px;
			}
			.header .date {
				text-align: right;
				color: #C4C4C4;
				font-family: Tahoma, Verdana, sans-serif;
				font-size: 12px;
			}
			.footer {
				background: #FDFDFD;
				color: #C4C4C4;
				font-family: Tahoma, Verdana, sans-serif;
				font-size: 12px;
			}
			.footer > td {
				border-top: 1px solid #F1F1F1;
			}
			.footer td table {
				width: 100%;
				max-width: 550px;
				margin: 0 auto;
				text-align: center;
				line-height: 1.6;
			}
			.footer a {
				text-decoration: none;
				color: #C4C4C4;
			}
			.footer .privacy td {
				padding: 10px 0;
			}
			.content {
				padding: 30px 10px;
			}
			.content table {
				color: #C4C4C4;
				font-family: Tahoma, Verdana, sans-serif;
				font-size: 16px;
				width: 100%;
				max-width: 550px;
				margin: 0 auto;
			}
			.button {
				background: #FFBB10;
				height: 40px;
				line-height: 40px;
				font-size: 16px;
				color: #fff;
				padding: 0 30px;
				display: inline-block;
				text-decoration: none;
			}
		</style>
	</head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<center>
        	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="height: 100%">
				<tr class="header">
					<td>
						<table>
							<tr>
								<td><img src="{{env('APP_URL') . 'images/logo.png'}}" alt="" height="50px"></td>
								<td class="date">April 8, 2016</td>
							</tr>
						</table>
					</td>
				</tr>
            	<tr>
                	<td class="content" valign="top">
						<table>
							<tr>
								<td>
									@yield('content')
								</td>
							</tr>
						</table>
                    </td>
                </tr>
				<tr class="footer">
					<td>
						<table>
							<tr>
								<td class="share">

								</td>
							</tr>
							<tr class="privacy">
								<td>
									<a href="http://thedepartureclub.com/terms-and-conditions/" target="_blank">TERMS AND CONDITIONS</a>
								</td>
							</tr>
							<tr>
								<td>
									HoboJet LLC, 15290 N 78th Way Scottsdale, AZ 85260
								</td>
							</tr>
						</table>
					</td>
				</tr>
            </table>
        </center>
    </body>
</html>
