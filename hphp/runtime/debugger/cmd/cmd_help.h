/*
   +----------------------------------------------------------------------+
   | HipHop for PHP                                                       |
   +----------------------------------------------------------------------+
   | Copyright (c) 2010-2014 Facebook, Inc. (http://www.facebook.com)     |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
*/

#ifndef incl_HPHP_EVAL_DEBUGGER_CMD_HELP_H_
#define incl_HPHP_EVAL_DEBUGGER_CMD_HELP_H_

#include "hphp/runtime/debugger/debugger_command.h"

namespace HPHP { namespace Eval {
///////////////////////////////////////////////////////////////////////////////

struct CmdHelp : DebuggerCommand {
  static void HelpAll(DebuggerClient&);
  static void HelpStarted(DebuggerClient&);

  CmdHelp() : DebuggerCommand(KindOfHelp) {}

  void list(DebuggerClient&) override;
  void help(DebuggerClient&) override;
  void onClient(DebuggerClient&) override;

private:
  bool processTutorial(DebuggerClient&);
};

///////////////////////////////////////////////////////////////////////////////
}}

#endif // incl_HPHP_EVAL_DEBUGGER_CMD_HELP_H_
