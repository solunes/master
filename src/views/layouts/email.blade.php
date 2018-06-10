<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Email')</title>
    <meta charset="utf-8">
    <style type="text/css">
      h1 { color: #555; font-family: Arial, Helvetica, sans-serif;margin-top: 16px;margin-bottom: 8px;word-break: break-word;font-size: 28px;line-height: 38px;font-weight: bold; }
      h2 { color: #555; font-family: Arial, Helvetica, sans-serif;margin-top: 16px;margin-bottom: 8px;word-break: break-word;font-size: 28px;line-height: 38px;font-weight: bold; }
      h3 { color: #555; font-family: Arial, Helvetica, sans-serif;margin-top: 16px;margin-bottom: 8px;word-break: break-word;font-size: 28px;line-height: 38px;font-weight: bold; }
      h4 { color: #555; font-family: Arial, Helvetica, sans-serif;margin-top: 16px;margin-bottom: 8px;word-break: break-word;font-size: 28px;line-height: 38px;font-weight: bold; }
      p { color: #555; font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 32px;word-break: break-word;font-size: 19px;line-height: 31px; }
    </style>
  </head>
  <body class="email-site">
    <table data-module="notification_default_xs_icon" class="email_section currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
      <tr>
        <td class="email_bg bg_light px py_lg editable" data-bgcolor="Light" style="font-size: 0;text-align: center;line-height: 100%;background-color: #e0e0e0;padding-top: 64px;padding-bottom: 64px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <!--[if (mso)|(IE)]>
            <table width="416" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                <![endif]-->
                <table class="content_section_xs" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 416px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                  <tr>
                    <td class="content_cell bg_white brounded bt_primary px py_md" data-bgcolor="White" data-border-top-color="Border Primary" style="font-size: 0;text-align: center;background-color: #ffffff;border-top: 4px solid {{ config('solunes.app_color') }};border-radius: 4px;padding-top: 32px;padding-bottom: 32px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                      <table class="column" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        <tr>
                          <td class="column_cell px py_xs text_primary text_center" data-color="Primary" style="vertical-align: top;color: {{ config('solunes.app_color') }};text-align: center;padding-top: 8px;padding-bottom: 8px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                            <p class="img_inline" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 16px;line-height: 100%;clear: both;">
                              <a href="{{ url('') }}" data-color="Primary" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;text-decoration: none;color: {{ config('solunes.app_color') }};font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;"><img src="{{ asset('assets/img/logo-email.png') }}" alt="{{ config('solunes.app_name') }}" style="max-width: 150px;-ms-interpolation-mode: bicubic;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;" /></a>
                            </p>
                          </td>
                        </tr>
                      </table>
                      <div class="column_row" style="font-size: 0;text-align: center;max-width: 624px;margin: 0 auto;">
                          <!--[if (mso)|(IE)]>
                          <table width="312" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
                            <tr>
                              <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                              <![endif]-->
                              <div class="col_2" style="vertical-align: top;display: inline-block;width: 100%;max-width: 312px;">
                                <table class="column" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                  <tr>
                                    <td class="column_cell bb_light" height="32" data-border-bottom-color="Border Light" style="vertical-align: top;border-bottom: 1px solid #dee0e1;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                      &nbsp;
                                    </td>
                                  </tr>
                                </table>
                                </div> <!--[if (mso)|(IE)]>
                              </td>
                            </tr>
                          </table> <![endif]-->
                        </div>
                        <table class="column" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                          <tr>
                            <td class="column_cell px py_md text_dark text_center editable" data-color="Dark" style="vertical-align: top;color: #333333;text-align: center;padding-top: 32px;padding-bottom: 32px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                              <table class="column column_inline" align="center" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;width: auto;margin: 0 auto;clear: both;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                <tr>
                                  <td class="column_cell bg_primary brounded_circle px py text_white text_center" data-bgcolor="Primary" data-color="White" style="vertical-align: top;background-color: {{ config('solunes.app_color') }};color: #ffffff;border-radius: 50%;text-align: center;padding-top: 16px;padding-bottom: 16px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                    <p class="img_full" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 0 !important;line-height: 100%;clear: both;">
                                      <img src="{{ asset('assets/admin/img/email-icons') }}/@yield('icon', 'Mail').png" width="48" height="48" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;display: block;width: 100%;margin: 0px auto;" />
                                    </p>
                                  </td>
                                </tr>
                              </table>
                              @yield('content')
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="content_cell" style="font-size: 0;text-align: center;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        <div class="column_row" style="font-size: 0;text-align: center;max-width: 624px;margin: 0 auto;">
                          <!--[if (mso)|(IE)]>
                          <table width="624" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
                            <tr>
                              <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                              <![endif]-->
                              <table class="column" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                <tr>
                                  <td class="column_cell px py_md text_secondary text_center" data-color="Secondary" style="vertical-align: top;color: #959ba0;text-align: center;padding-top: 32px;padding-bottom: 32px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                    <p class="mb_xs text_link" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 8px;word-break: break-word;font-size: 16px;line-height: 26px;">
                                      ©{{ date('Y') }} {{ config('solunes.app_name') }}.<br /> <a class="text_xs" href="@yield('unsuscribe-email')" data-color="Secondary" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;text-decoration: underline;color: #959ba0;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 14px;line-height: 22px;"><span data-color="Secondary" style="color: #959ba0;text-decoration: none;">Dejar de recibir emails</span></a>
                                    </p>
                                  </td>
                                </tr>
                                </table> <!--[if (mso)|(IE)]>
                              </td>
                            </tr>
                          </table> <![endif]-->
                        </div>
                      </td>
                    </tr>
                  </table> <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </table> <![endif]-->
          </td>
        </tr>
      </table>
  </body>
</html>