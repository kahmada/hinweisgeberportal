import Link from 'next/link';

export default function Home() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center p-4">
      <div className="max-w-4xl w-full bg-white rounded-lg shadow-xl p-12">
        <h1 className="text-5xl font-bold text-gray-900 mb-4">
          Hinweisgeberportal
        </h1>
        <p className="text-xl text-gray-600 mb-12">
          Secure Whistleblower Portal - Submit and manage anonymous reports
        </p>

        <div className="grid md:grid-cols-2 gap-6">
          <Link
            href="/report"
            className="group block p-8 bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors"
          >
            <h2 className="text-2xl font-bold text-white mb-2">
              Submit Report
            </h2>
            <p className="text-blue-100">
              Submit an anonymous whistleblower report securely
            </p>
            <div className="mt-4 text-white group-hover:translate-x-2 transition-transform">
              →
            </div>
          </Link>

          <Link
            href="/admin/reports"
            className="group block p-8 bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors"
          >
            <h2 className="text-2xl font-bold text-white mb-2">
              Admin Dashboard
            </h2>
            <p className="text-gray-300">
              View and manage submitted reports
            </p>
            <div className="mt-4 text-white group-hover:translate-x-2 transition-transform">
              →
            </div>
          </Link>
        </div>
      </div>
    </div>
  );
}
