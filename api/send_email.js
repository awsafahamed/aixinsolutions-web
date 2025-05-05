// api/send-email.js
const nodemailer = require('nodemailer');

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ success: false, message: 'Method not allowed' });
  }

  const { username, lastname, email, services, message } = req.body;

  // Create transporter
  const transporter = nodemailer.createTransport({
    host: 'smtp.hostinger.com',
    port: 465,
    secure: true,
    auth: {
      user: 'hello@aixinsolutions.com',
      pass: 'Hello@123Aixin'
    }
  });

  try {
    await transporter.sendMail({
      from: 'hello@aixinsolutions.com',
      to: 'info@aixinsolutions.com',
      subject: 'New Contact Form Submission',
      html: `...your HTML email template...`,
      text: `...your plain text version...`
    });

    return res.status(200).json({ success: true, message: 'Your message has been sent successfully!' });
  } catch (error) {
    return res.status(500).json({ success: false, message: `Message could not be sent. Error: ${error.message}` });
  }
}