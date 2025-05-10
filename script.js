function sendDataToPHP() {
  var spreadsheetId = "1GkqMNgm_ebaDXBArXUDyRAzK6Ct_Dl63mh0ttfj-Pc8";
  var spreadsheet = SpreadsheetApp.openById(spreadsheetId);
  var sheet = spreadsheet.getSheetByName("respuestas");

  // Verifica si la hoja existe
  if (!sheet) {
    Logger.log("Error: No se encontró la hoja 'respuestas'");
    return;
  }

  var data = sheet.getDataRange().getValues();
  var controlCell = sheet.getRange("Z1");
  var lastProcessedTimestamp = controlCell.getValue() || "";
  
  // Verificar que haya datos para procesar
  if (data.length <= 1) {
    Logger.log("No hay datos para procesar");
    return;
  }
  
  var payload = [];
  var newDataFound = false;
  var latestTimestamp = lastProcessedTimestamp;

  for (var i = 1; i < data.length; i++) {
    var currentTimestamp = data[i][0];
    
    // Verificar que el timestamp sea válido
    if (!currentTimestamp) continue;
    
    // Actualizar el último timestamp procesado si es más reciente
    if (currentTimestamp > latestTimestamp) {
      latestTimestamp = currentTimestamp;
    }
    
    if (currentTimestamp > lastProcessedTimestamp) {
      try {
        payload.push({
          marca_temporal: formatDateIfNeeded(data[i][0]),
          nombre_apellidos: String(data[i][1] || ""),
          correo_electronico: String(data[i][2] || ""),
          tipo_identificacion: String(data[i][3] || ""),
          numero_identificacion: String(data[i][4] || ""),
          numero_contacto: String(data[i][5] || ""),
          fecha_nacimiento: formatDateIfNeeded(data[i][6]),
          institucion: String(data[i][7] || ""),
          programa_academico: String(data[i][8] || ""),
          modalidad_estudio: String(data[i][9] || ""),
          semestre: String(data[i][10] || ""),
          labora_actualmente: String(data[i][11] || ""),
          entidad_donde_labora: String(data[i][12] || ""),
          ideas_mejorar_cecarmun: String(data[i][13] || ""),
          experiencia_comite_organizador: String(data[i][14] || ""),
          primera_opcion_rol: String(data[i][15] || ""),
          segunda_opcion_rol: String(data[i][16] || ""),
          compromiso_eventos: String(data[i][17] || ""),
          leyo_terminos: String(data[i][18] || ""),
          autoriza_habeas_data: String(data[i][19] || "")
        });
        newDataFound = true;
      } catch (e) {
        Logger.log("Error procesando fila " + i + ": " + e.toString());
      }
    }
  }

  if (payload.length > 0 && newDataFound) {
    var url = "https://server-2swv.onrender.com/index.php";
    var options = {
      method: "POST",
      contentType: "application/json",
      payload: JSON.stringify(payload),
      muteHttpExceptions: true // Para capturar errores HTTP
    };

    try {
      var response = UrlFetchApp.fetch(url, options);
      var responseText = response.getContentText();
      var responseCode = response.getResponseCode();
      
      Logger.log("Respuesta del servidor (" + responseCode + "): " + responseText);
      
      if (responseCode >= 200 && responseCode < 300) {
        // Solo actualizar el timestamp si la respuesta fue exitosa
        controlCell.setValue(latestTimestamp);
        Logger.log("Timestamp actualizado: " + latestTimestamp);
      } else {
        Logger.log("Error en la respuesta del servidor. No se actualizó el timestamp.");
      }
    } catch (e) {
      Logger.log("Error al enviar datos: " + e.toString());
    }
  } else {
    Logger.log("No se encontraron nuevos datos para enviar");
  }
}

// Función auxiliar para formatear fechas si es necesario
function formatDateIfNeeded(value) {
  if (value instanceof Date) {
    return Utilities.formatDate(value, "GMT", "yyyy-MM-dd HH:mm:ss");
  }
  return String(value || "");
}

function createTrigger() {
  // Primero eliminar triggers existentes para evitar duplicados
  var triggers = ScriptApp.getProjectTriggers();
  for (var i = 0; i < triggers.length; i++) {
    if (triggers[i].getHandlerFunction() === "sendDataToPHP") {
      ScriptApp.deleteTrigger(triggers[i]);
    }
  }
  
  // Crear nuevo trigger
  ScriptApp.newTrigger("sendDataToPHP")
    .timeBased()
    .everyMinutes(1)
    .create();
    
  Logger.log("Trigger creado exitosamente");
}