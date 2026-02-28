import { useState, useRef, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { ArrowLeft, Lock, User, Eye, EyeOff, Shield, RefreshCw, CheckCircle, XCircle } from 'lucide-react';
import { adminApi } from '../lib/adminApi';
import { cookie } from '../lib/cookie';

const generatePuzzle = () => {
  const puzzles = [
    { text: '5 + 3 = ?', answer: '8' },
    { text: '10 - 4 = ?', answer: '6' },
    { text: '2 x 4 = ?', answer: '8' },
    { text: '12 - 7 = ?', answer: '5' },
    { text: '3 + 6 = ?', answer: '9' },
    { text: '15 - 8 = ?', answer: '7' },
    { text: '4 x 3 = ?', answer: '12' },
    { text: '20 / 4 = ?', answer: '5' },
  ];
  return puzzles[Math.floor(Math.random() * puzzles.length)];
};

const parseSessionTimeout = (value) => {
  const parsed = Number(value);
  return Number.isFinite(parsed) && parsed > 0 ? parsed : 60;
};

const CaptchaPuzzle = ({ onVerify }) => {
  const [puzzle, setPuzzle] = useState(generatePuzzle());
  const [userAnswer, setUserAnswer] = useState('');
  const [status, setStatus] = useState('idle');
  const [isShaking, setIsShaking] = useState(false);
  const [isSuccess, setIsSuccess] = useState(false);
  const inputRef = useRef(null);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (userAnswer.trim() === puzzle.answer) {
      setStatus('verified');
      setIsSuccess(true);
      onVerify(true);
    } else {
      setStatus('error');
      setIsShaking(true);
      setUserAnswer('');
      setTimeout(() => {
        setStatus('idle');
        setIsShaking(false);
      }, 1500);
    }
  };

  const refreshPuzzle = (e) => {
    e.preventDefault();
    setPuzzle(generatePuzzle());
    setStatus('idle');
    setUserAnswer('');
    setIsSuccess(false);
  };

  if (isSuccess) {
    return (
      <div className="mt-4 p-4 bg-green-50 rounded-xl border border-green-200 flex items-center gap-3">
        <div className="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
          <CheckCircle className="w-5 h-5 text-green-600" />
        </div>
        <div>
          <p className="font-semibold text-green-700">Terverifikasi</p>
          <p className="text-xs text-green-600">Anda manusia bukan robot</p>
        </div>
      </div>
    );
  }

  return (
    <div className="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
      <div className="flex items-center justify-between mb-3">
        <div className="flex items-center gap-2">
          <Shield className="w-4 h-4 text-slate-500" />
          <span className="text-sm font-medium text-slate-700">Verifikasi Keamanan</span>
        </div>
        <button
          onClick={refreshPuzzle}
          className="flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded-lg transition-colors"
        >
          <RefreshCw className="w-3 h-3" />
          Baru
        </button>
      </div>

      <div className="flex items-center gap-3">
        <div className="flex-1">
          <p className="text-lg font-bold text-slate-800 mb-2 font-mono">{puzzle.text}</p>

          <div className="flex gap-2">
            <input
              ref={inputRef}
              type="text"
              inputMode="numeric"
              value={userAnswer}
              onChange={(e) => {
                setUserAnswer(e.target.value);
                if (status === 'error') setStatus('idle');
              }}
              placeholder="?"
              onKeyDown={(e) => e.key === 'Enter' && handleSubmit(e)}
              className={`w-16 px-2 py-2 rounded-lg border-2 text-center font-mono text-base font-bold outline-none transition-all ${status === 'error' && isShaking
                  ? 'border-red-400 bg-red-50 text-red-700'
                  : 'border-slate-200 bg-white text-slate-800 placeholder:text-slate-300 focus:border-blue-500'
                }`}
            />
            <button
              type="button"
              onClick={handleSubmit}
              disabled={!userAnswer}
              className="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 rounded-lg text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Cek
            </button>
          </div>
        </div>
      </div>

      {status === 'error' && !isShaking && (
        <p className="text-red-600 text-xs mt-2 flex items-center gap-1">
          <XCircle className="w-3 h-3" />
          Jawaban salah, coba lagi
        </p>
      )}
    </div>
  );
};

export default function SuperAdminLogin() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [isVerified, setIsVerified] = useState(false);
  const [showCaptcha, setShowCaptcha] = useState(false);
  const [sessionTimeoutMinutes, setSessionTimeoutMinutes] = useState(60);
  const [announcement, setAnnouncement] = useState('');
  const [maintenanceMode, setMaintenanceMode] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    let active = true;
    adminApi.getSettings()
      .then((settings) => {
        if (!active) return;
        setSessionTimeoutMinutes(parseSessionTimeout(settings?.session_timeout_minutes));
        setAnnouncement((settings?.superadmin_announcement || '').trim());
        setMaintenanceMode(String(settings?.maintenance_mode || '').toLowerCase() === 'true');
      })
      .catch(() => {
        if (!active) return;
        setSessionTimeoutMinutes(60);
        setAnnouncement('');
        setMaintenanceMode(false);
      });
    return () => { active = false; };
  }, []);

  const handleInputChange = (field, value) => {
    if (field === 'username') {
      setUsername(value);
      if (value.length > 0 && !showCaptcha) setShowCaptcha(true);
    }
    if (field === 'password') {
      setPassword(value);
      if (!showCaptcha && value.length > 0) setShowCaptcha(true);
    }
  };

  const handleLogin = async (e) => {
    e.preventDefault();

    if (!username || !password) {
      setError('Masukkan username dan password');
      return;
    }

    if (!isVerified) {
      setError('Silakan verifikasi keamanan terlebih dahulu');
      setShowCaptcha(true);
      return;
    }

    setError('');
    setIsLoading(true);

    try {
      const admin = await adminApi.login(username, password);

      if (admin.role !== 'super_admin') {
        setError('Akses ditolak. Hanya Super Admin yang dapat masuk.');
        setIsVerified(false);
        setIsLoading(false);
        return;
      }

      cookie.setWithMinutes('super_admin', admin, sessionTimeoutMinutes);
      navigate('/superadmin/dashboard');
    } catch (err) {
      setError(err.message || 'Username atau password yang Anda masukkan salah');
      setIsVerified(false);
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center p-4 bg-slate-900 relative overflow-hidden">
      <div className="w-full max-w-md relative z-10">
        <div className="bg-white rounded-2xl overflow-hidden shadow-xl">
          <div className="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-center">
            <div className="w-20 h-20 bg-white rounded-2xl mx-auto mb-3 flex items-center justify-center shadow-lg">
              <Shield className="w-12 h-12 text-blue-600" />
            </div>
            <h2 className="text-xl font-bold text-white">Super Admin</h2>
            <p className="text-blue-100 text-sm mt-1">Kelola Sistem Administrator</p>
          </div>

          <div className="p-6">
            {maintenanceMode && (
              <div className="mb-4 rounded-xl border border-rose-200 bg-gradient-to-r from-rose-50 to-red-50 px-4 py-3 shadow-sm">
                <div className="flex items-start gap-3">
                  <div className="w-9 h-9 rounded-lg bg-red-100 text-red-700 flex items-center justify-center font-bold">!</div>
                  <div>
                    <p className="text-xs font-bold uppercase tracking-wider text-red-700">Mode Perawatan Aktif</p>
                    <p className="text-sm font-semibold text-red-900 leading-relaxed">Akses admin non-super sedang ditangguhkan.</p>
                  </div>
                </div>
              </div>
            )}
            {announcement && (
              <div className="mb-4 rounded-xl border border-amber-200 bg-gradient-to-r from-amber-50 to-yellow-50 px-4 py-3 shadow-sm">
                <div className="flex items-start gap-3">
                  <div className="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold">!</div>
                  <div>
                    <p className="text-xs font-bold uppercase tracking-wider text-amber-700">Pengumuman Superadmin</p>
                    <p className="text-sm font-semibold text-amber-900 leading-relaxed">{announcement}</p>
                  </div>
                </div>
              </div>
            )}
            <form onSubmit={handleLogin} className="space-y-4">
              <div>
                <label className="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <User className="h-5 w-5 text-blue-400" />
                  </div>
                  <input
                    type="text"
                    value={username}
                    onChange={(e) => handleInputChange('username', e.target.value)}
                    className="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg outline-none focus:border-blue-500 transition-all text-sm"
                    placeholder="Username"
                    autoComplete="username"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <Lock className="h-5 w-5 text-blue-400" />
                  </div>
                  <input
                    type={showPassword ? 'text' : 'password'}
                    value={password}
                    onChange={(e) => handleInputChange('password', e.target.value)}
                    className="w-full pl-10 pr-12 py-2.5 border border-slate-300 rounded-lg outline-none focus:border-blue-500 transition-all text-sm"
                    placeholder="Password"
                    autoComplete="current-password"
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-500 hover:text-blue-700"
                  >
                    {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                  </button>
                </div>
              </div>

              {showCaptcha && <CaptchaPuzzle onVerify={setIsVerified} />}

              {error && (
                <div className="text-red-600 text-sm bg-red-50 py-2 px-3 rounded-lg border border-red-100 flex items-center gap-2">
                  <XCircle className="w-4 h-4 flex-shrink-0" />
                  {error}
                </div>
              )}

              <button
                type="submit"
                disabled={isLoading}
                className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2"
              >
                {isLoading ? (
                  <>
                    <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                    Memproses...
                  </>
                ) : (
                  <>
                    <Shield className="w-4 h-4" />
                    Masuk
                  </>
                )}
              </button>
            </form>
          </div>

          <div className="px-6 py-4 bg-slate-50 border-t border-slate-100">
            <button
              onClick={() => navigate('/')}
              className="w-full flex items-center justify-center gap-2 text-blue-700 hover:text-blue-900 text-sm font-semibold"
            >
              <ArrowLeft className="w-4 h-4" />
              Kembali ke Beranda
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
