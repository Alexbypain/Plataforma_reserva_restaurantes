package plataforma.reserva.restaurantes.domain.repository;

import com.itextpdf.kernel.pdf.PdfWriter;
import com.itextpdf.layout.Document;
import com.itextpdf.layout.element.Paragraph;
import org.springframework.stereotype.Service;
import plataforma.reserva.restaurantes.domain.entities.Reserva;

import java.io.ByteArrayOutputStream;

@Service
public class PdfRepository {

    public byte[] generarPdfReserva(Reserva reserva) {
        try (ByteArrayOutputStream baos = new ByteArrayOutputStream()) {
            PdfWriter writer = new PdfWriter(baos);
            Document document = new Document(new com.itextpdf.kernel.pdf.PdfDocument(writer));

            // Agregar contenido al PDF
            document.add(new Paragraph("Información de la Reserva"));
            document.add(new Paragraph("Restaurante: " + reserva.getRestaurante().getNombre()));
            document.add(new Paragraph("Dirección: " + reserva.getRestaurante().getDireccion()));
            document.add(new Paragraph("Fecha: " + reserva.getFecha()));
            document.add(new Paragraph("Cantidad de Personas: " + reserva.getCantidad_personas()));
            document.add(new Paragraph("Motivo: " + reserva.getMotivo()));
            document.add(new Paragraph("Requisitos Especiales: " + reserva.getRequisitos_especiales()));
            document.add(new Paragraph("Alergias: " + reserva.getAlergias()));

            document.close();
            return baos.toByteArray();
        } catch (Exception e) {
            throw new RuntimeException("Error al generar el PDF", e);
        }
    }
}