package plataforma.reserva.restaurantes.services;

import com.itextpdf.kernel.pdf.PdfWriter;
import com.itextpdf.kernel.pdf.PdfDocument;
import com.itextpdf.layout.Document;
import com.itextpdf.layout.element.Paragraph;
import plataforma.reserva.restaurantes.domain.entities.Reserva;

import org.springframework.stereotype.Service;

import java.io.ByteArrayOutputStream;

@Service
public class PdfService {

    public byte[] generarPdfReserva(Reserva reserva) {
        try (ByteArrayOutputStream baos = new ByteArrayOutputStream()) {
            PdfWriter writer = new PdfWriter(baos);
            PdfDocument pdf = new PdfDocument(writer);
            Document document = new Document(pdf);

            document.add(new Paragraph("Detalles de la Reserva"));
            document.add(new Paragraph("ID: " + reserva.getId()));
            document.add(new Paragraph("Usuario: " + reserva.getUsuario().getNombre()));
            document.add(new Paragraph("Restaurante: " + reserva.getRestaurante().getNombre()));
            document.add(new Paragraph("Fecha y hora: " + reserva.getFecha().toString()));
            document.add(new Paragraph("Motivo: " + reserva.getMotivo()));
            document.add(new Paragraph("Cantidad de personas: " + reserva.getCantidad_personas()));
            document.add(new Paragraph("Requisitos especiales: " + reserva.getRequisitos_especiales()));
            document.add(new Paragraph("Alergias: " + reserva.getAlergias()));

            document.close();
            return baos.toByteArray();
        } catch (Exception e) {
            throw new RuntimeException("Error al generar PDF de reserva", e);
        }
    }
}
